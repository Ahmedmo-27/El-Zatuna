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
     * Toggle favorite for an item (webinar, bundle, or product)
     * Consolidated endpoint replacing /toggle/{id} and /toggle2
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
