<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\FileResource;
use App\Http\Resources\TextLessonResource;
use App\Models\Api\TextLesson;
use App\Models\WebinarChapter;
use Illuminate\Http\Request;

class TextLessonController extends Controller
{
    /**
     * Get text lesson content (enrolled users).
     *
     * @OA\Get(
     *     path="/v1/panel/text-lessons/{lesson}",
     *     summary="Get text lesson",
     *     tags={"Panel", "My courses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="lesson", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Text lesson content"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function show($id)
    {
        $textLesson = TextLesson::where('id', $id)
            ->where('status', WebinarChapter::$chapterActive)->first();
        abort_unless($textLesson, 404);

        if ($error = $textLesson->canViewError()) {
            //       return $this->failure($error, 403, 403);
        }
        $resource = new TextLessonResource($textLesson);
        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $resource);
    }
}
