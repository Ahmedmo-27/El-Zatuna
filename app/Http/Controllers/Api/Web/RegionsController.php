<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Api\Controller;
use App\Models\Region;
use App\Support\ApiPayloadCache;
use Illuminate\Support\Facades\DB;

class RegionsController extends Controller
{
    public function countries()
    {
        $cacheKey = 'api:v1:regions:countries:' . ApiPayloadCache::localeTag();
        $countries = ApiPayloadCache::rememberShared($cacheKey, 'regions_countries', function () {
            return Region::select(DB::raw('*, ST_AsText(geo_center) as geo_center'))
                ->where('type', Region::$country)
                ->get()
                ->toArray();
        });

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $countries);
    }

    /**
     * Mobile dialing codes per country (static reference).
     */
    public function countriesMobileCode()
    {
        $cacheKey = 'api:v1:regions:countries:code:' . ApiPayloadCache::localeTag();
        $data = ApiPayloadCache::rememberShared($cacheKey, 'regions_countries_code', function () {
            return getCountriesMobileCode();
        });

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $data);
    }

    public function provinces($id = null)
    {
        return $this->region(Region::$province, 'country_id', $id, 'regions_provinces', 'provinces');
    }

    public function cities($id = null)
    {
        return $this->region(Region::$city, 'province_id', $id, 'regions_cities', 'cities');
    }

    public function districts($id = null)
    {
        return $this->region(Region::$district, 'city_id', $id, 'regions_districts', 'districts');
    }

    private function region($type, $super_region_type, $super_region_id, string $ttlConfigKey, string $segment)
    {
        $parentKey = $super_region_id ?? 'all';
        $cacheKey = 'api:v1:regions:' . $segment . ':' . $parentKey . ':' . ApiPayloadCache::localeTag();

        $rows = ApiPayloadCache::rememberShared($cacheKey, $ttlConfigKey, function () use ($type, $super_region_type, $super_region_id) {
            $query = Region::select(DB::raw('*, ST_AsText(geo_center) as geo_center'))
                ->where('type', $type);

            if ($super_region_id) {
                $query->where($super_region_type, $super_region_id);
            }

            return $query->get()->toArray();
        });

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $rows);
    }
}

