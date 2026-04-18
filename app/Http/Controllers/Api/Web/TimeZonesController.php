<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Api\Controller;
use App\Support\ApiPayloadCache;

class TimeZonesController extends Controller
{
    public function index()
    {
        $cacheKey = 'api:v1:timezones:' . ApiPayloadCache::localeTag();
        $list = ApiPayloadCache::rememberShared($cacheKey, 'timezones', function () {
            return getListOfTimezones();
        });

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $list);
    }
}
