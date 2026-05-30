<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Api\Controller;
use App\Models\Api\Bundle;
use App\Models\Cart;
use App\Models\LiveSession;
use App\Models\Api\Product;
use App\Models\ProductOrder;
use App\Models\ReserveMeeting;
use App\Models\Subscribe;
use App\Models\Ticket;
use App\Models\Api\Webinar;
use App\Models\WebinarChapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Calculation\Web;

class AddCartController extends Controller
{
    public $cookieKey = 'carts';

    public function storeUserLiveSessionCart($user, $data)
    {
        $liveSessionId = $data['item_id'];

        validateParam($data, [
            'item_id' => Rule::exists('live_sessions', 'id')->where('status', 'published')
        ]);

        $liveSession = LiveSession::where('id', $liveSessionId)
            ->where('status', 'published')
            ->first();

        if (empty($liveSession)) {
            return apiResponse2(0, 'not_found', 'Live session not found or not available');
        }

        if (empty($user)) {
            return apiResponse2(0, 'unauthorized', trans('auth.not_login'));
        }

        if (Cart::where('creator_id', $user->id)->where('live_session_id', $liveSessionId)->count()) {
            return apiResponse2(0, 'already_in_cart', 'this item is in the cart');
        }

        Cart::updateOrCreate([
            'creator_id' => $user->id,
            'live_session_id' => $liveSessionId,
        ], [
            'created_at' => time(),
        ]);

        return 'ok';
    }

    public function storeUserWebinarCart($user, $data)
    {
        $webinar_id = $data['item_id'];
        $ticket_id = $data['ticket_id'] ?? null;


        validateParam($data, [
            'item_id' => Rule::exists('webinars', 'id')
                ->where('status', 'active')->where('private', false)

        ]);

        // Use withoutGlobalScope to bypass university/faculty filtering when adding to cart
        $webinar = Webinar::withoutGlobalScope('universityFaculty')->find($webinar_id);
        
        if (empty($webinar)) {
            return apiResponse2(0, 'not_found', 'Course not found or not available');
        }
        
        if (empty($user)) {
            return apiResponse2(0, 'unauthorized', trans('auth.not_login'));
        }

        $checkCourseForSale = $webinar->checkWebinarForSale($user, true);

        if ($checkCourseForSale != 'ok') {
            return $checkCourseForSale;
        }

        $activeSpecialOffer = $webinar->activeSpecialOffer();

        Cart::updateOrCreate([
            'creator_id' => $user->id,
            'webinar_id' => $webinar_id,
        ], [
            'ticket_id' => $ticket_id,
            'special_offer_id' => !empty($activeSpecialOffer) ? $activeSpecialOffer->id : null,
            'created_at' => time()
        ]);

        return 'ok';
    }

    public function storeUserBundleCart($user, $data)
    {
        $bundle_id = $data['item_id'];
        $ticket_id = $data['ticket_id'] ?? null;

        validateParam($data, [
            'item_id' => Rule::exists('bundles', 'id')
                ->where('status', 'active')

        ]);

        $bundle = Bundle::where('id', $bundle_id)
            ->where('status', 'active')
            ->first();

        if (empty($bundle)) {
            return apiResponse2(0, 'not_found', 'Bundle not found or not available');
        }
        
        if (empty($user)) {
            return apiResponse2(0, 'unauthorized', trans('auth.not_login'));
        }

        $checkCourseForSale = $bundle->checkWebinarForSale($user);

        if ($checkCourseForSale != 'ok') {
            return $checkCourseForSale;
        }

        $activeSpecialOffer = $bundle->activeSpecialOffer();

        Cart::updateOrCreate([
            'creator_id' => $user->id,
            'bundle_id' => $bundle_id,
        ], [
            'ticket_id' => $ticket_id,
            'special_offer_id' => !empty($activeSpecialOffer) ? $activeSpecialOffer->id : null,
            'created_at' => time()
        ]);

        return 'ok';
    }

    public function storeUserProductCart($user, $data)
    {
        $product_id = $data['item_id'];
        $specifications = $data['specifications'] ?? null;
        $quantity = $data['quantity'] ?? 1;

        validateParam($data, [
            'item_id' => Rule::exists('products', 'id')
                ->where('status', 'active')

        ]);
        $product = Product::where('id', $product_id)
            ->where('status', 'active')
            ->first();

        if (empty($product)) {
            return apiResponse2(0, 'not_found', 'Product not found or not available');
        }
        
        if (empty($user)) {
            return apiResponse2(0, 'unauthorized', trans('auth.not_login'));
        }

        $checkProductForSale = $product->checkProductForSale($user);

        if ($checkProductForSale != 'ok') {
            return $checkProductForSale;
        }

        $activeDiscount = $product->getActiveDiscount();

        $productOrder = ProductOrder::updateOrCreate([
            'product_id' => $product->id,
            'seller_id' => $product->creator_id,
            'buyer_id' => $user->id,
            'sale_id' => null,
            'status' => 'pending',
        ], [
            'specifications' => $specifications ? json_encode($specifications) : null,
            'quantity' => $quantity,
            'discount_id' => !empty($activeDiscount) ? $activeDiscount->id : null,
            'created_at' => time()
        ]);

        Cart::updateOrCreate([
            'creator_id' => $user->id,
            'product_order_id' => $productOrder->id,
        ], [
            'product_discount_id' => !empty($activeDiscount) ? $activeDiscount->id : null,
            'created_at' => time()
        ]);

        return 'ok';
    }

    public function storeUserChapterCart($user, $data)
    {
        $chapter_id = $data['item_id'];

        $chapter = WebinarChapter::where('id', $chapter_id)
            ->where('status', WebinarChapter::$chapterActive)
            ->with('webinar')
            ->first();

        if (empty($chapter) || empty($chapter->webinar)) {
            return apiResponse2(0, 'not_found', trans('cart.course_not_found'));
        }

        if ($chapter->isFirstSection() || (float) $chapter->price <= 0) {
            return apiResponse2(0, 'free_section', trans('cart.course_not_free'));
        }

        if ($chapter->webinar->checkUserHasBought($user) || $chapter->checkUserHasBought($user)) {
            return apiResponse2(0, 'already_bought', trans('site.you_bought_webinar'));
        }

        if ($chapter->webinar->creator_id == $user->id) {
            return apiResponse2(0, 'cant_purchase', trans('cart.cant_purchase_your_course'));
        }

        Cart::updateOrCreate([
            'creator_id' => $user->id,
            'chapter_id' => $chapter->id,
        ], [
            'webinar_id' => null,
            'file_id' => null,
            'created_at' => time()
        ]);

        return 'ok';
    }

    public function storeUserSubscribeCart($user, $data)
    {
        $subscribe_id = $data['item_id'];

        validateParam($data, [
            'item_id' => Rule::exists('subscribes', 'id'),
        ]);

        $subscribe = Subscribe::where('id', $subscribe_id)->first();

        if (empty($subscribe)) {
            return apiResponse2(0, 'not_found', 'Subscription not found');
        }

        if (empty($user)) {
            return apiResponse2(0, 'unauthorized', trans('auth.not_login'));
        }

        Cart::updateOrCreate([
            'creator_id' => $user->id,
            'subscribe_id' => $subscribe->id,
        ], [
            'webinar_id' => null,
            'bundle_id' => null,
            'file_id' => null,
            'chapter_id' => null,
            'created_at' => time(),
        ]);

        return 'ok';
    }

    /**
    * Add item to cart (course, bundle, product, course section, subscription, or live session).
     *
     * @OA\Post(
     *     path="/v1/panel/cart",
     *     summary="Add to cart",
     *     tags={"Panel", "Cart"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"item_id","item_name"},
    *             @OA\Property(property="item_id", type="integer", description="Course/bundle/product/section (chapter)/live session ID"),
    *             @OA\Property(property="item_name", type="string", enum={"webinar","bundle","product","chapter","subscribe","live_session"}, description="webinar=full course, chapter=paid course section, subscribe=subscription plan, live_session=booked session"),
     *             @OA\Property(property="ticket_id", type="integer", nullable=true, description="For webinar only"),
     *             @OA\Property(property="specifications", type="object", nullable=true),
     *             @OA\Property(property="quantity", type="integer", nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Added to cart or already in cart/not available (status=already_in_cart). Body: success, status."),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(Request $request)
    {
        $user = apiAuth();
        
        validateParam($request->all(), [
            'item_id' => 'required',
            'item_name' => 'required|in:webinar,bundle,product,chapter,subscribe,live_session',
            'ticket_id' => 'nullable',
            'specifications' => 'nullable',
            'quantity' => 'nullable'
        ]);
        
        $rr = $request->input('item_name') . '_id';
        if (Cart::where($rr, $request->input('item_id'))->where('creator_id', $user->id)->count()) {
            return apiResponse2(0, 'already_in_cart', 'this item is in the cart');
        }

        $data = $request->except('_token');
        $item_name = $data['item_name'];

        $result = null;

        if ($item_name == 'webinar') {
            $result = $this->storeUserWebinarCart($user, $data);
        } elseif ($item_name == 'product') {
            $result = $this->storeUserProductCart($user, $data);
        } elseif ($item_name == 'bundle') {
            $result = $this->storeUserBundleCart($user, $data);
        } elseif ($item_name == 'chapter') {
            $result = $this->storeUserChapterCart($user, $data);
        } elseif ($item_name == 'subscribe') {
            $result = $this->storeUserSubscribeCart($user, $data);
        } elseif ($item_name == 'live_session') {
            $result = $this->storeUserLiveSessionCart($user, $data);
        }

        if ($result != 'ok') {
            return $result;
        }
        
        return apiResponse2(1, 'stored', trans('cart.cart_add_success_msg'));

    }

    public function destroy($id)
    {
        if (auth()->check()) {
            $user_id = auth()->id();

            $cart = Cart::where('id', $id)
                ->where('creator_id', $user_id)
                ->first();

            if (!empty($cart)) {
                if (!empty($cart->reserve_meeting_id)) {
                    $reserve = ReserveMeeting::where('id', $cart->reserve_meeting_id)
                        ->where('user_id', $user_id)
                        ->first();

                    if (!empty($reserve)) {
                        $reserve->delete();
                    }
                }

                $cart->delete();
            }
        } else {
            $carts = Cookie::get($this->cookieKey);

            if (!empty($carts)) {
                $carts = json_decode($carts, true);

                if (!empty($carts[$id])) {
                    unset($carts[$id]);
                }

                Cookie::queue($this->cookieKey, json_encode($carts), 30 * 24 * 60);
            }
        }

        return apiResponse2(1, 'deleted', trans('api.public.deleted'));
    }
}
