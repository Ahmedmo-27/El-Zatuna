<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\PurchaseResource;
use App\Mixins\Cashback\CashbackRules;
use App\Models\Api\Sale;
use App\Models\Api\Webinar;
use App\Models\Api\Gift;
use App\Models\WebinarPartnerTeacher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebinarsController extends Controller
{
    public function show($id)
    {
        $user = apiAuth();

        $webinar = Webinar::query()->where('id', $id)
            ->where(function (Builder $query) use ($user) {
                $query->where('creator_id', $user->id);
                $query->orWhere('teacher_id', $user->id);
            })->first();

        if (!empty($webinar)) {
            $cashbackRules = null;

            $data = $webinar->brief;

            if (!empty($data["price"]) and getFeaturesSettings('cashback_active') and (empty($user) or !$user->disable_cashback)) {
                $cashbackRulesMixin = new CashbackRules($user);
                $cashbackRules = $cashbackRulesMixin->getRules('courses', $data["id"], $data["type"], null, null);
            }

            $data["cashbackRules"] = $cashbackRules;

            return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $data);
        }

        return apiResponse2(0, 'invalid', trans('api.public.invalid'));
    }

    public function list(Request $request, $id = null)
    {
        return [
            'my_classes' => $this->myClasses($request),
            'purchases' => $this->purchases($request),
            'organizations' => $this->organizations($request),
            'invitations' => $this->invitations($request),
        ];
    }

    public function myClasses(Request $request)
    {
        $user = apiAuth();

        $query = Webinar::where(function ($query) use ($user) {
            if ($user->isTeacher()) {
                $query->where('teacher_id', $user->id);
            } elseif ($user->isOrganization()) {
                $query->where('creator_id', $user->id);
            }
        })->handleFilters();

        $paginatedData = apiPagination(
            $query,
            $request,
            function ($webinar) {
                return $webinar->brief;
            },
            url('/api/' . config('app.api_version', 'development') . '/panel/webinars')
        );

        return $paginatedData;
    }

    public function indexPurchases(Request $request)
    {
        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'),
            [
                'purchases' => $this->purchases($request)
            ]);
    }

    public function free(Request $request, $id)
    {
        $user = apiAuth();

        $course = Webinar::where('id', $id)
            ->where('status', 'active')
            ->first();
        abort_unless($course, 404);


        $checkCourseForSale = $course->checkCourseForSale($user);

        if ($checkCourseForSale != 'ok') {
            return apiResponse2(0, $checkCourseForSale, trans('api.course.purchase.' . $checkCourseForSale));
        }

        if (!empty($course->price) and $course->price > 0) {
            return apiResponse2(0, 'not_free', trans('api.cart.not_free'));


        }

        Sale::create([
            'buyer_id' => $user->id,
            'seller_id' => $course->creator_id,
            'webinar_id' => $course->id,
            'type' => Sale::$webinar,
            'payment_method' => Sale::$credit,
            'amount' => 0,
            'total_amount' => 0,
            'created_at' => time(),
        ]);

        return apiResponse2(1, 'enrolled', trans('api.webinar.enrolled'));

    }

    public function purchases(Request $request = null)
    {
        $user = apiAuth();

        $giftsIds = Gift::query()->where('email', $user->email)
            ->where('status', 'active')
            ->whereNull('product_id')
            ->where(function ($query) {
                $query->whereNull('date');
                $query->orWhere('date', '<', time());
            })
            ->whereHas('sale')
            ->pluck('id')
            ->toArray();

        $query = Sale::query()
            ->where(function ($query) use ($user, $giftsIds) {
                $query->where('sales.buyer_id', $user->id);
                $query->orWhereIn('sales.gift_id', $giftsIds);
            })
            ->whereNull('sales.refund_at')
            ->where('access_to_purchased_item', true)
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereNotNull('sales.webinar_id')
                        ->where('sales.type', 'webinar')
                        ->whereHas('webinar', function ($query) {
                            $query->where('status', 'active');
                        });
                });
                $query->orWhere(function ($query) {
                    $query->whereNotNull('sales.bundle_id')
                        ->where('sales.type', 'bundle')
                        ->whereHas('bundle', function ($query) {
                            $query->where('status', 'active');
                        });
                });
                $query->orWhere(function ($query) {
                    $query->whereNotNull('gift_id');
                    $query->whereHas('gift');
                });
            })
            ->with([
                'webinar' => function ($query) {
                    $query->with([
                        'files',
                        'reviews' => function ($query) {
                            $query->where('status', 'active');
                        },
                        'category',
                        'teacher' => function ($query) {
                            $query->select('id', 'full_name');
                        },
                    ]);
                    $query->withCount([
                        'sales' => function ($query) {
                            $query->whereNull('refund_at');
                        }
                    ]);
                },
                'bundle' => function ($query) {
                    $query->with([
                        'reviews' => function ($query) {
                            $query->where('status', 'active');
                        },
                        'category',
                        'teacher' => function ($query) {
                            $query->select('id', 'full_name');
                        },
                    ]);
                }
            ]);

        // If no request provided (backward compatibility), return collection without pagination
        if ($request === null) {
            $sales = $query->orderBy('created_at', 'desc')->get();
            $this->processSalesData($sales);
            return PurchaseResource::collection($sales);
        }

        // With request, apply pagination
        $paginatedData = apiPagination(
            $query,
            $request,
            function ($sale) {
                $this->processSaleItem($sale);
                return (new PurchaseResource($sale))->resolve();
            },
            url('/api/' . config('app.api_version', 'development') . '/panel/webinars/purchases')
        );

        return $paginatedData;
    }

    private function processSaleItem($sale)
    {
        $time = time();
        $purchaseDate = $sale->created_at;

        if (!empty($sale->gift_id)) {
            $gift = $sale->gift;
            $purchaseDate = $gift->date;

            $sale->webinar_id = $gift->webinar_id;
            $sale->bundle_id = $gift->bundle_id;

            $sale->webinar = !empty($gift->webinar_id) ? $gift->webinar : null;
            $sale->bundle = !empty($gift->bundle_id) ? $gift->bundle : null;

            $sale->gift_recipient = !empty($gift->receipt) ? $gift->receipt->full_name : $gift->name;
            $sale->gift_sender = $sale->buyer->full_name;
            $sale->gift_date = $gift->date;
        }

        if (!empty($sale->webinar)) {
            if ($sale->webinar->access_days > 0) {
                $sale->expired = strtotime("+{$sale->webinar->access_days} days", $purchaseDate) < $time;
                $sale->expired_at = strtotime("+{$sale->webinar->access_days} days", $purchaseDate);
            } else {
                $sale->expired = false;
                $sale->expired_at = null;
            }
        } else if (!empty($sale->bundle)) {
            if ($sale->bundle->access_days > 0) {
                $sale->expired = strtotime("+{$sale->bundle->access_days} days", $purchaseDate) < $time;
                $sale->expired_at = strtotime("+{$sale->bundle->access_days} days", $purchaseDate);
            } else {
                $sale->expired = false;
                $sale->expired_at = null;
            }
        }
    }

    private function processSalesData($sales)
    {
        foreach ($sales as $sale) {
            $this->processSaleItem($sale);
        }
    }

    public function invitations(Request $request)
    {
        $user = apiAuth();

        $invitedWebinarIds = WebinarPartnerTeacher::where('teacher_id', $user->id)->pluck('webinar_id')->toArray();
        
        $query = Webinar::where('status', 'active')
            ->whereIn('id', $invitedWebinarIds)
            ->handleFilters();

        $paginatedData = apiPagination(
            $query,
            $request,
            function ($webinar) {
                return $webinar->brief;
            },
            url('/api/' . config('app.api_version', 'development') . '/panel/webinars/invitations')
        );

        return $paginatedData;
    }

    public function organizations(Request $request)
    {
        $user = apiAuth();

        $query = Webinar::where('creator_id', $user->organ_id)
            ->where('status', 'active')
            ->handleFilters();

        $paginatedData = apiPagination(
            $query,
            $request,
            function ($webinar) {
                return $webinar->brief;
            },
            url('/api/' . config('app.api_version', 'development') . '/panel/webinars/organization')
        );

        return $paginatedData;
    }

    public function indexOrganizations()
    {

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'),
            [
                'webinars' => $this->organizations()
            ]);

    }

    public function offlinePurchases()
    {
        $user = apiAuth();
        $sales = Sale::where('sales.buyer_id', $user->id)
            ->whereNull('sales.refund_at')
            ->where('access_to_purchased_item', true)
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereNotNull('sales.webinar_id')
                        ->where('sales.type', 'webinar')
                        ->whereHas('webinar', function ($query) {
                            $query->where('status', 'active');
                        });
                });
                $query->orWhere(function ($query) {
                    $query->whereNotNull('sales.bundle_id')
                        ->where('sales.type', 'bundle')
                        ->whereHas('bundle', function ($query) {
                            $query->where('status', 'active');
                        });
                });
            })->with([
                'webinar' => function ($query) {
                    $query->with([
                        'files',
                        'reviews' => function ($query) {
                            $query->where('status', 'active');
                        },
                        'category',
                        'teacher' => function ($query) {
                            $query->select('id', 'full_name');
                        },
                    ]);
                    $query->withCount([
                        'sales' => function ($query) {
                            $query->whereNull('refund_at');
                        }
                    ]);
                },
                'bundle' => function ($query) {
                    $query->with([
                        'reviews' => function ($query) {
                            $query->where('status', 'active');
                        },
                        'category',
                        'teacher' => function ($query) {
                            $query->select('id', 'full_name');
                        },
                    ]);
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'),
            [
                $sales
            ]);
    }

}
