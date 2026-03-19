<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\WebinarChapterResource;
use App\Models\Api\Webinar;
use App\Models\WebinarChapter;
use Illuminate\Http\Request;

class WebinarChapterController extends Controller
{
    /**
     * List chapters and content items for a course (for watching).
     *
     * @OA\Get(
     *     path="/v1/panel/webinars/{webinar}/chapters",
     *     summary="List course chapters",
     *     tags={"Panel", "My courses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="webinar", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Chapters with items (files, sessions, text lessons, quizzes)"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index($webinar_id)
    {
        $chapters = WebinarChapter::where('webinar_id', $webinar_id)
            ->where('status', WebinarChapter::$chapterActive)
            ->orderBy('order', 'asc')
            ->with([
                'chapterItems' => function ($query) {
                    $query->orderBy('order', 'asc');
                }
            ])
            ->get();
        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), WebinarChapterResource::collection($chapters));
    }
}
