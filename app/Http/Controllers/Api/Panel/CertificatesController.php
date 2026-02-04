<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\CertificateResource;
use App\Mixins\Certificate\MakeCertificate;
use App\Models\Api\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Api\Quiz;
use App\Models\Api\QuizzesResult;
use App\Models\Reward;
use App\Models\RewardAccounting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class CertificatesController extends Controller
{
    public function created(Request $request)
    {
        $user = apiAuth();

        $query = Quiz::where('creator_id', $user->id)
            ->where('status', Quiz::ACTIVE)
            ->handleFilters();

        $paginatedData = apiPagination(
            $query,
            $request,
            function ($quiz) {
                return (new CertificateResource($quiz))->resolve();
            },
            url('/api/' . config('app.api_version', 'development') . '/panel/certificates/created')
        );

        return apiResponse2(1, 'retrieved', trans('public.retrieved'), $paginatedData);
    }

    public function students(Request $request)
    {
        $user = apiAuth();

        $quizzes = Quiz::where('creator_id', $user->id)
            ->pluck('id')->toArray();

        $query = Certificate::whereIn('quiz_id', $quizzes);

        $paginatedData = apiPagination(
            $query,
            $request,
            function ($certificate) {
                return $certificate->details;
            },
            url('/api/' . config('app.api_version', 'development') . '/panel/certificates/students')
        );

        return apiResponse2(1, 'retrieved', trans('public.retrieved'), $paginatedData);
    }

    public function achievements(Request $request)
    {
        $user = apiAuth();
        
        $query = QuizzesResult::where('user_id', $user->id)
            ->where('status', QuizzesResult::$passed)
            ->whereHas('quiz', function ($query) {
                $query->where('status', Quiz::ACTIVE);
            });

        $paginatedData = apiPagination(
            $query,
            $request,
            function ($result) {
                return array_merge(
                    $result->details,
                    ['certificate' => $result->certificate->brief ?? null]
                );
            },
            url('/api/' . config('app.api_version', 'development') . '/panel/certificates/achievements')
        );

        return apiResponse2(1, 'retrieved', trans('public.retrieved'), $paginatedData);
    }

    public function makeCertificate($quizResultId)
    {
        $user = apiAuth();

        $makeCertificate = new MakeCertificate();

        $quizResult = QuizzesResult::where('id', $quizResultId)
            ->where('user_id', $user->id)
            ->where('status', QuizzesResult::$passed)
            ->with(['quiz' => function ($query) {
                $query->with(['webinar']);
            }])
            ->first();

        if (!empty($quizResult)) {
            return $makeCertificate->makeQuizCertificate($quizResult);
        }

        abort(404);
    }


}

