<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Api\Objects\WebinarObj;
use App\Models\Api\FeatureWebinar;
use Illuminate\Http\Request;

class FeatureWebinarController
{
    /**
     * List featured courses (home / home_categories).
     *
     * @OA\Get(
     *     path="/v1/featured-courses",
     *     summary="List featured courses",
     *     tags={"Discovery"},
     *     @OA\Response(response=200, description="List of featured webinars")
     * )
     */
    public function index(Request $request){

        $webinars=FeatureWebinar::whereIn('page', ['home', 'home_categories'])
        ->where('status', 'publish') 
        ->handleFilters()
        ->get()->map(function ($item) {
            return $item->webinar->brief;
        });

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $webinars);

    }
   
    
}
