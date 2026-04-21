<?php

namespace App\Http\Controllers\Api;

use App\Models\Bundle;
use App\Models\Category;
use App\Models\Discount;
use App\Models\DiscountBundle;
use App\Models\DiscountCategory;
use App\Models\DiscountCourse;
use App\Models\Webinar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BulkDiscountController extends Controller
{
    /**
     * Create a bulk/seasonal discount via API
     * 
     * Endpoint: POST /api/v1/admin/discounts/bulk
     * 
     * Required Headers:
     * - Authorization: Bearer <JWT token (admin)>
     * - Content-Type: application/json
     * - Accept: application/vnd.lms.v1+json
     * 
     * Request Body:
     * {
     *   "title": "Black Friday 2024",
     *   "code": "BLACKFRIDAY2024",
     *   "discount_type": "percentage",
     *   "source": "all",
     *   "apply_to_items": "all",
     *   "percent": 10,
     *   "max_amount": 100,
     *   "minimum_order": 0,
     *   "count": 0,
     *   "expired_at": "2024-12-31 23:59:59"
     * }
     */
    public function store(Request $request)
    {
        // Verify admin authorization
        $user = auth('api')->user();
        if (!$user || !($user->role?->is_admin ?? false)) {
            return $this->apiResponse(false, 'unauthorized', trans('public.unauthorized'), 403);
        }

        // Validate input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:64|unique:discounts',
            'discount_type' => 'required|in:percentage,fixed_amount',
            'source' => 'required|in:all,course,bundle,category,meeting,product',
            'apply_to_items' => 'required|in:all,courses,bundles,categories,products',
            'percent' => 'nullable|numeric|min:0|max:100',
            'amount' => 'nullable|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'minimum_order' => 'nullable|numeric|min:0',
            'count' => 'nullable|integer|min:0',
            'expired_at' => 'required|date',
        ]);

        // Validate that either percent or amount is provided
        if (empty($validated['percent']) && empty($validated['amount'])) {
            return $this->apiResponse(
                false,
                'validation_error',
                trans('update.either_percent_or_amount_required'),
                422
            );
        }

        try {
            DB::beginTransaction();

            // Convert expiration date to UTC
            $expiredAt = convertTimeToUTCzone($validated['expired_at'], getTimezone());

            // Create the main discount
            $discount = Discount::create([
                'creator_id' => auth('api')->id(),
                'title' => $validated['title'],
                'discount_type' => $validated['discount_type'],
                'source' => $validated['source'],
                'code' => $validated['code'],
                'percent' => (!empty($validated['percent']) && $validated['percent'] > 0) ? $validated['percent'] : 0,
                'amount' => !empty($validated['amount']) ? convertPriceToDefaultCurrency($validated['amount']) : null,
                'max_amount' => !empty($validated['max_amount']) ? convertPriceToDefaultCurrency($validated['max_amount']) : null,
                'minimum_order' => !empty($validated['minimum_order']) ? convertPriceToDefaultCurrency($validated['minimum_order']) : null,
                'count' => (!empty($validated['count']) && $validated['count'] > 0) ? $validated['count'] : 0,
                'user_type' => 'all_users',
                'status' => 'active',
                'expired_at' => $expiredAt->getTimestamp(),
                'created_at' => time(),
            ]);

            // Apply bulk discount to specific item types
            $applyTo = $validated['apply_to_items'];

            if ($applyTo === 'all' || $applyTo === 'courses') {
                $courses = Webinar::where('status', 'active')->get();
                foreach ($courses as $course) {
                    DiscountCourse::create([
                        'discount_id' => $discount->id,
                        'course_id' => $course->id,
                        'created_at' => time(),
                    ]);
                }
            }

            if ($applyTo === 'all' || $applyTo === 'bundles') {
                $bundles = Bundle::where('status', 'active')->get();
                foreach ($bundles as $bundle) {
                    DiscountBundle::create([
                        'discount_id' => $discount->id,
                        'bundle_id' => $bundle->id,
                        'created_at' => time(),
                    ]);
                }
            }

            if ($applyTo === 'all' || $applyTo === 'categories') {
                $categories = Category::where('status', 'active')->get();
                foreach ($categories as $category) {
                    DiscountCategory::create([
                        'discount_id' => $discount->id,
                        'category_id' => $category->id,
                        'created_at' => time(),
                    ]);
                }
            }

            DB::commit();

            return $this->apiResponse(
                true,
                'discount_created',
                trans('update.discount_created_successful'),
                200,
                [
                    'discount_id' => $discount->id,
                    'title' => $discount->title,
                    'code' => $discount->code,
                    'discount_type' => $discount->discount_type,
                    'source' => $discount->source,
                    'status' => 'active',
                    'expired_at' => dateTimeFormat($discount->expired_at),
                ]
            );

        } catch (\Exception $e) {
            DB::rollBack();

            return $this->apiResponse(
                false,
                'error',
                trans('update.something_went_wrong') . ': ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Get bulk discount statistics
     */
    public function statistics()
    {
        $user = auth('api')->user();
        if (!$user || !($user->role?->is_admin ?? false)) {
            return $this->apiResponse(false, 'unauthorized', trans('public.unauthorized'), 403);
        }

        $totalDiscounts = Discount::count();
        $activeDiscounts = Discount::where('status', 'active')
            ->where('expired_at', '>', time())
            ->count();
        $expiredDiscounts = Discount::where('expired_at', '<=', time())->count();

        return $this->apiResponse(
            true,
            'success',
            trans('admin/main.statistics'),
            200,
            [
                'total_discounts' => $totalDiscounts,
                'active_discounts' => $activeDiscounts,
                'expired_discounts' => $expiredDiscounts,
            ]
        );
    }
}
