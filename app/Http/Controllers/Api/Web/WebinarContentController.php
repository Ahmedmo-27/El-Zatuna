<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\QuizResource;
use App\Models\Api\Quiz;
use App\Models\Api\Webinar;
use App\Support\ApiPayloadCache;
use Illuminate\Http\Request;

class WebinarContentController extends Controller
{
    /**
     * List quizzes for a course (public course page).
     *
     * @OA\Get(
     *     path="/v1/courses/{id}/quizzes",
     *     summary="List course quizzes",
     *     tags={"Courses"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="List of quizzes")
     * )
     */
    public function quizzes($webinar_id)
    {
        $cacheKey = 'api:v1:courses:quizzes:' . $webinar_id . ':' . ApiPayloadCache::localeTag();
        $data = ApiPayloadCache::rememberForGuest($cacheKey, 'courses_quizzes', function () use ($webinar_id) {
            $quizzes = Quiz::where('webinar_id', $webinar_id)->where('status', 'active')->get();

            return QuizResource::collection($quizzes)->resolve();
        });

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $data);
    }

    /**
     * List certificates available for a course (completion, quiz certificates).
     *
     * @OA\Get(
     *     path="/v1/courses/{id}/certificates",
     *     summary="List course certificates",
     *     tags={"Courses"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="List of certificate types/links")
     * )
     */
    public function certificates($webinar_id)
    {
        $cacheKey = 'api:v1:courses:certificates:' . $webinar_id . ':' . ApiPayloadCache::localeTag();
        $certificates = ApiPayloadCache::rememberShared($cacheKey, 'courses_certificates', function () use ($webinar_id) {
            $webinar = Webinar::find($webinar_id);

            $quizzes = Quiz::with('webinar')->where('webinar_id', $webinar_id)->where('status', 'active')
                ->where('certificate', 1)->get();
            $certificates = $quizzes->map(function ($quiz) {
                return [
                    'type' => 'quiz',
                    'link' => route('quiz.show', $quiz->id),
                    'title' => $quiz->title,
                    'created_at' => $quiz->created_at,

                ];
            });
            if ($webinar && $webinar->certificate == 1) {
                $certificates->push([
                    'type' => 'completion',
                    'title' => $webinar->title,
                    'created_at' => $webinar->created_at,

                ]);
            }

            return $certificates->values()->all();
        });

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $certificates);
    }
}
