<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\Api\Web\WebinarController;
use App\Models\Api\Favorite;
use App\Models\Api\Webinar;
use App\Models\Bundle;
use App\User;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    /**
     * Toggle favorite for an item (webinar, bundle, or product).
     *
     * @OA\Post(
     *     path="/v1/panel/favorites/toggle",
     *     summary="Toggle favorite",
     *     tags={"Panel"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"item_id","item_type"},
     *             @OA\Property(property="item_id", type="integer"),
     *             @OA\Property(property="item_type", type="string", enum={"webinar","bundle","product"})
     *         )
     *     ),
     *     @OA\Response(response=200, description="favored or unfavored"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Item not found")
     * )
     */
    public function toggle(Request $request)
    {
        validateParam($request->all(), [
            'item_id' => 'required|integer',
            'item_type' => 'required|in:webinar,bundle,product'
        ]);

        $userId = apiAuth()->id;
        $itemType = $request->input('item_type');
        $itemId = $request->input('item_id');

        // Find the item based on type
        if ($itemType == 'webinar') {
            $item = Webinar::where('id', $itemId)
                ->where('status', 'active')
                ->first();
        } else if ($itemType == 'bundle') {
            $item = Bundle::where('id', $itemId)
                ->where('status', 'active')
                ->first();
        } else if ($itemType == 'product') {
            $item = \App\Models\Product::where('id', $itemId)
                ->where('status', 'active')
                ->first();
        }

        if (!$item) {
            return apiResponse2(0, 'not_found', trans('public.not_found'), null, null, 404);
        }

        // Check if already favorited
        $isFavorite = Favorite::where($itemType . '_id', $item->id)
            ->where('user_id', $userId)
            ->first();

        $isFavorited = false;
        
        if (empty($isFavorite)) {
            // Add to favorites
            Favorite::create([
                'user_id' => $userId,
                $itemType . '_id' => $item->id,
                'created_at' => time()
            ]);
            $status = 'favored';
            $isFavorited = true;
        } else {
            // Remove from favorites
            $isFavorite->delete();
            $status = 'unfavored';
            $isFavorited = false;
        }

        return apiResponse2(1, 'toggled', trans('favorite.' . $status), [
            'is_favorited' => $isFavorited
        ]);
    }

    /**
     * List user's favorites (paginated).
     *
     * @OA\Get(
     *     path="/v1/panel/favorites",
     *     summary="List favorites",
     *     tags={"Panel"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Paginated favorites"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function list(Request $request)
    {
        $user = apiAuth();
        
        // Get base query
        $query = Favorite::where('user_id', $user->id);
        
        // Apply pagination with transformer
        $paginatedData = apiPagination(
            $query,
            $request,
            function ($favorite) {
                return [
                    'id' => $favorite->id,
                    'webinar' => $favorite->webinar ? $favorite->webinar->brief : null,
                    'created_at' => $favorite->created_at
                ];
            },
            url('/api/' . config('app.api_version', 'development') . '/panel/favorites')
        );
        
        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $paginatedData);
    }

    /**
     * Remove a favorite by ID.
     *
     * @OA\Delete(
     *     path="/v1/panel/favorites/{id}",
     *     summary="Remove favorite",
     *     tags={"Panel"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Removed"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function destroy($id)
    {
        $user = apiAuth();
        $favorite = favorite::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (empty($favorite)) {
            abort(404);
        }
        $favorite->delete();
        return apiResponse2(1, 'deleted', trans('api.public.deleted'));
    }
}
