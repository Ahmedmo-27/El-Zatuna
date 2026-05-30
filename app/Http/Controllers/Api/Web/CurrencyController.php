<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Api\Controller;
use App\Mixins\Financial\MultiCurrency;
use App\Support\ApiPayloadCache;

class CurrencyController extends Controller
{
    /**
     * List supported currencies (multi-currency config).
     */
    public function list()
    {
        $cacheKey = 'api:v1:currency:list:' . ApiPayloadCache::localeTag();
        $data = ApiPayloadCache::rememberShared($cacheKey, 'currency_list', function () {
            $multiCurrency = new MultiCurrency();

            return $multiCurrency->getCurrencies();
        });

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $data);
    }
}
