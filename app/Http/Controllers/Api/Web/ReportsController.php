<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Api\Controller;
use App\Support\ApiPayloadCache;

class ReportsController extends Controller
{
    /**
     * Get report reasons (for reporting a course).
     *
     * @OA\Get(
     *     path="/v1/courses/reports/reasons",
     *     summary="List report reasons",
     *     tags={"Courses"},
     *     @OA\Response(response=200, description="List of report reasons")
     * )
     */
    public function index()
    {
        $cacheKey = 'api:v1:courses:reports:reasons:' . ApiPayloadCache::localeTag();
        $reasons = ApiPayloadCache::rememberShared($cacheKey, 'report_reasons', function () {
            return getReportReasons();
        });

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $reasons);
    }
}

