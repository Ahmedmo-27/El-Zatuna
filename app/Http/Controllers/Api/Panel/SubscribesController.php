<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Api\Controller;
use App\Mixins\Logs\UserLoginHistoryMixin;
use App\Models\Api\Bundle;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Accounting;
use App\Models\Api\Webinar;
use App\User;
use App\Models\SubscribeUse;
use App\Models\OrderItem;
use App\Models\PaymentChannel;
use App\Models\Api\Subscribe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;


class SubscribesController extends Controller
{
    public function index(Request $request)
    {
        $user = apiAuth();
        $subscribes = Subscribe::all()->map(function ($subscribe) {
            return $subscribe->details;
        });

        $activeSubscribe = Subscribe::getActiveSubscribe($user->id);
        $dayOfUse = Subscribe::getDayOfUse($user->id);
        //   $has_active_subscribe=($activeSubscribe)?true:false ;

        $data = [
            'subscribes' => $subscribes,
            'subscribed' => ($activeSubscribe) ? true : false,
            'subscribe_id' => ($activeSubscribe) ? $activeSubscribe->id : null,
            'subscribed_title' => ($activeSubscribe) ? $activeSubscribe->title : null,
            'remained_downloads' => ($activeSubscribe) ? $activeSubscribe->usable_count - $activeSubscribe->used_count : null,
            'days_remained' => ($activeSubscribe) ? $activeSubscribe->days - $dayOfUse : null,
            'dayOfUse' => $dayOfUse,
        ];
        return apiResponse2(1, 'retrieved', trans('public.retrieved'), $data);
    }

    public function webPayGenerator(Request $request)
    {

        validateParam($request->all(), [
            'subscribe_id' => ['required', Rule::exists('subscribes', 'id')]
        ]);

        $user = apiAuth();
        $activeSubscribe = Subscribe::getActiveSubscribe($user->id);

        if ($activeSubscribe) {

            return apiResponse2(0, 'has_active_subscribe',
                trans('site.you_have_active_subscribe'), null,
                trans('public.request_failed')

            );
        }

        return apiResponse2(1, 'generated', trans('api.link.generated'),
            [
                'link' => URL::signedRoute('my_api.web.subscribe', [
                    apiAuth()->id,
                    $request->input('subscribe_id')
                ])

            ]
        );
    }

    public function webPayRender(Request $request, User $user, $subscribe_id)
    {
        $id = $subscribe_id;
        $subscribe = Subscribe::find($id);
        $amount = $subscribe->price;

        Auth::login($user, true);

        $userLoginHistoryMixin = new UserLoginHistoryMixin();
        $userLoginHistoryMixin->storeUserLoginHistory($user);

        return view('api.subscribe', compact('amount', 'id'))->withHeaders('X-Frame-Options', 'ALLOWALL');
    }

    public function pay(Request $request)
    {
        validateParam($request->all(), [
            'subscribe_id' => ['required', Rule::exists('subscribes', 'id')]
        ]);
        $paymentChannels = PaymentChannel::where('status', 'active')->get();

        $subscribe_id = $request->input('subscribe_id');
        $subscribe = Subscribe::find($subscribe_id);

        $user = apiAuth();
        $activeSubscribe = Subscribe::getActiveSubscribe($user->id);

        if ($activeSubscribe) {

            return apiResponse2(0, 'has_active_subscribe', trans('api.subscribe.has_active_subscribe'));
        }

        $financialSettings = getFinancialSettings();
        $tax = $financialSettings['tax'] ?? 0;

        $amount = $subscribe->price;

        $taxPrice = $tax ? $amount * $tax / 100 : 0;

        $order = Order::create([
            "user_id" => $user->id,
            "status" => Order::$pending,
            'tax' => $taxPrice,
            'commission' => 0,
            "amount" => $amount,
            "total_amount" => $amount + $taxPrice,
            "created_at" => time(),
        ]);

        OrderItem::updateOrCreate([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'subscribe_id' => $subscribe->id,
        ], [
            'amount' => $order->amount,
            'total_amount' => $amount + $taxPrice,
            'tax' => $tax,
            'tax_price' => $taxPrice,
            'commission' => 0,
            'commission_price' => 0,
            'created_at' => time(),
        ]);

        $razorpay = false;
        foreach ($paymentChannels as $paymentChannel) {
            if ($paymentChannel->class_name == 'Razorpay') {
                $razorpay = true;
            }
        }


        $data = [
            //  'pageTitle' => trans('public.checkout_page_title'),
            'paymentChannels' => $paymentChannels,
            'total' => $order->total_amount,
            'order' => $order,
            // 'count' => 1,
            'userCharge' => $user->getAccountingCharge(),
            'razorpay' => $razorpay
        ];

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $data);

    }

    /**
     * Apply subscription to purchase a course or bundle
     * Consolidated endpoint for subscription application
     */
    public function apply(Request $request)
    {
        validateParam($request->all(), [
            'plan_id' => 'required|integer|exists:subscribes,id',
            'item_id' => 'nullable|integer',
            'item_type' => 'nullable|in:webinar,bundle',
            'payment_method' => 'nullable|string',
            'auto_renew' => 'nullable|boolean'
        ]);

        $user = apiAuth();
        $subscribe = Subscribe::getActiveSubscribe($user->id);
        
        // If item_id and item_type are provided, apply subscription to specific item
        if ($request->has('item_id') && $request->has('item_type')) {
            return $this->applyToItem($request, $user, $subscribe);
        }
        
        // Otherwise, create new subscription plan
        return $this->createSubscription($request, $user);
    }

    /**
     * Apply existing subscription to a specific item (webinar or bundle)
     */
    private function applyToItem(Request $request, $user, $subscribe)
    {
        $itemType = $request->input('item_type');
        $itemId = $request->input('item_id');

        if (!$subscribe) {
            return apiResponse2(0, 'no_active_subscribe',
                trans('site.you_dont_have_active_subscribe'), null,
                trans('public.request_failed')
            );
        }

        // Find the item
        if ($itemType == 'webinar') {
            $item = Webinar::where('id', $itemId)
                ->where('status', 'active')
                ->where('private', false)
                ->first();
        } elseif ($itemType == 'bundle') {
            $item = Bundle::where('id', $itemId)
                ->where('status', 'active')
                ->first();
        }

        if (!$item) {
            return apiResponse2(0, 'not_found', trans('public.not_found'), null, null, 404);
        }

        if (!$item->subscribe) {
            return apiResponse2(0, 'not_subscribable', trans('api.course.not_subscribable'));
        }

        // Check if item can be added to cart
        $checkCourseForSale = $item->checkWebinarForSale($user, ($itemType == 'webinar'));

        if ($checkCourseForSale == 'free') {
            return apiResponse2(0, 'free', trans('api.cart.free'));
        }

        if ($checkCourseForSale != 'ok') {
            return apiResponse2(0, $checkCourseForSale,
                $item->checkCourseForSaleMsg(), null,
                trans('public.request_failed')
            );
        }

        // Create sale record
        $sale = Sale::create([
            'buyer_id' => $user->id,
            'seller_id' => $item->creator_id,
            $itemType . '_id' => $item->id,
            'subscribe_id' => $subscribe->id,
            'type' => $itemType == 'webinar' ? Sale::$webinar : Sale::$bundle,
            'payment_method' => Sale::$subscribe,
            'amount' => 0,
            'total_amount' => 0,
            'created_at' => time(),
        ]);

        Accounting::createAccountingForSaleWithSubscribe($item, $subscribe, $itemType . '_id');

        SubscribeUse::create([
            'user_id' => $user->id,
            'subscribe_id' => $subscribe->id,
            $itemType . '_id' => $item->id,
            'sale_id' => $sale->id,
        ]);

        return apiResponse2(1, 'subscribed',
            trans('cart.success_pay_msg_subscribe'),
            null,
            trans('cart.success_pay_title')
        );
    }

    /**
     * Create a new subscription plan
     */
    private function createSubscription(Request $request, $user)
    {
        $planId = $request->input('plan_id');
        $subscribe = Subscribe::find($planId);

        if (!$subscribe) {
            return apiResponse2(0, 'not_found', trans('public.not_found'), null, null, 404);
        }

        $activeSubscribe = Subscribe::getActiveSubscribe($user->id);

        if ($activeSubscribe) {
            return apiResponse2(0, 'has_active_subscribe',
                trans('api.subscribe.has_active_subscribe'), null,
                trans('public.request_failed')
            );
        }

        $financialSettings = getFinancialSettings();
        $tax = $financialSettings['tax'] ?? 0;
        $amount = $subscribe->price;
        $taxPrice = $tax ? $amount * $tax / 100 : 0;

        $order = Order::create([
            "user_id" => $user->id,
            "status" => Order::$pending,
            'tax' => $taxPrice,
            'commission' => 0,
            "amount" => $amount,
            "total_amount" => $amount + $taxPrice,
            "created_at" => time(),
        ]);

        OrderItem::updateOrCreate([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'subscribe_id' => $subscribe->id,
        ], [
            'amount' => $order->amount,
            'total_amount' => $amount + $taxPrice,
            'tax' => $tax,
            'tax_price' => $taxPrice,
            'commission' => 0,
            'commission_price' => 0,
            'created_at' => time(),
        ]);

        $paymentChannels = PaymentChannel::where('status', 'active')->get();
        $razorpay = false;
        foreach ($paymentChannels as $paymentChannel) {
            if ($paymentChannel->class_name == 'Razorpay') {
                $razorpay = true;
            }
        }

        $data = [
            'subscription' => [
                'id' => $subscribe->id,
                'plan' => $subscribe->title,
                'start_date' => date('Y-m-d'),
                'end_date' => date('Y-m-d', strtotime('+' . $subscribe->days . ' days')),
                'auto_renew' => $request->input('auto_renew', false)
            ],
            'paymentChannels' => $paymentChannels,
            'total' => $order->total_amount,
            'order' => $order,
            'userCharge' => $user->getAccountingCharge(),
            'razorpay' => $razorpay
        ];

        return apiResponse2(1, 'subscription_created', trans('api.public.retrieved'), $data);
    }

}


