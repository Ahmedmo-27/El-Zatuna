<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Webinar;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request, $slug)
    {
        $userId = auth()->id();
        $favorited = false;
        $isAjaxRequest = ($request->ajax() || $request->wantsJson());

        $webinar = Webinar::where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!empty($webinar)) {

            $favoriteRow = Favorite::where('webinar_id', $webinar->id)
                ->where('user_id', $userId)
                ->first();

            if (empty($favoriteRow)) {
                Favorite::create([
                    'user_id' => $userId,
                    'webinar_id' => $webinar->id,
                    'created_at' => time()
                ]);

                $favorited = true;
            } else {
                $favoriteRow->delete();

                $favorited = false;
            }

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => $favorited ? 'Course added to your favorites.' : 'Course removed from your favorites.',
                'status' => 'success',
            ];

            if ($isAjaxRequest) {
                return response()->json([
                    'code' => 200,
                    'is_favorite' => $favorited,
                    'title' => $toastData['title'],
                    'msg' => $toastData['msg'],
                ], 200);
            }

            return back()->with(['toast' => $toastData]);
        }

        $errorToastData = [
            'title' => 'Not found',
            'msg' => 'Course not found.',
            'status' => 'error',
        ];

        if ($isAjaxRequest) {
            return response()->json([
                'code' => 404,
                'title' => $errorToastData['title'],
                'msg' => $errorToastData['msg'],
            ], 404);
        }

        return back()->with(['toast' => $errorToastData]);
    }
}
