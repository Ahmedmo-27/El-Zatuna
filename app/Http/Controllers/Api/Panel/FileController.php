<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\FileResource;
use App\Models\Api\File;
use App\Models\WebinarChapter;

class FileController extends Controller
{
    /**
     * Get file (video/document) details for playback or download (enrolled users).
     *
     * @OA\Get(
     *     path="/v1/panel/files/{file}",
     *     summary="Get course file",
     *     tags={"Panel", "My courses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="file", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="File details and access URL"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Not enrolled or no access"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function show($file_id)
    {
        $file = File::where('id', $file_id)
            ->where('files.status', WebinarChapter::$chapterActive)
            ->first();

        abort_unless($file, 404);

        if ($error = $file->canViewError()) {
            return $this->failure($error, 403, 403);
        }

        $resource = new FileResource($file);
        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $resource);
    }
}
