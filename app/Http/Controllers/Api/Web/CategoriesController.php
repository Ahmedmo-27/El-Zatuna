<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Api\Controller;
use App\Models\Api\Category;
use App\Models\Api\TrendCategory;
use App\Models\Api\Webinar;
use App\Support\ApiPayloadCache;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * List categories.
     *
     * @OA\Get(
     *     path="/v1/categories",
     *     summary="List categories",
     *     tags={"Discovery"},
     *     @OA\Response(response=200, description="Categories with count")
     * )
     */
    public function index(Request $request)
    {
        $cacheKey = 'api:v1:categories:index:' . ApiPayloadCache::localeTag();
        $payload = ApiPayloadCache::rememberShared($cacheKey, 'categories_index', function () {
            $categories = Category::whereNull('parent_id')->get()
                ->map(function ($category) {
                    return $category->details;
                });

            return [
                'count' => $categories->count(),
                'categories' => $categories->values()->all(),
            ];
        });

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $payload);
    }

    /**
     * List trend categories.
     *
     * @OA\Get(
     *     path="/v1/trend-categories",
     *     summary="List trend categories",
     *     tags={"Discovery"},
     *     @OA\Response(response=200, description="Trend categories with count")
     * )
     */
    public function trendCategory()
    {
        $cacheKey = 'api:v1:categories:trend:' . ApiPayloadCache::localeTag();
        $payload = ApiPayloadCache::rememberShared($cacheKey, 'categories_trend', function () {
            $categories = TrendCategory::orderBy('created_at', 'desc')
                ->get()
                ->map(function ($trendCategories) {
                    return $trendCategories->details;
                });

            return [
                'count' => $categories->count(),
                'categories' => $categories->values()->all(),
            ];
        });

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $payload);
    }

    /**
     * Get webinars (courses) by category.
     *
     * @OA\Get(
     *     path="/v1/categories/{id}/webinars",
     *     summary="Get courses by category",
     *     tags={"Discovery"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Category filters and webinars"),
     *     @OA\Response(response=404, description="Category not found")
     * )
     */
    public function categoryWebinar(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            abort(404);
        }

        $cacheKey = 'api:v1:categories:' . $id . ':webinars:' . ApiPayloadCache::requestFingerprint() . ':' . ApiPayloadCache::localeTag();
        $payload = ApiPayloadCache::rememberForGuest($cacheKey, 'categories_webinars', function () use ($category) {
            $webinars = Webinar::where('category_id', $category->id)->handleFilters()->get()
                ->map(function ($webinar) {
                    return $webinar->brief;
                });

            return [
                'filters' => $category->filters->map(function ($filter) {
                    return [
                        'id' => $filter->id,
                        'title' => $filter->title,
                        'options' => $filter->options->map(function ($option) {
                            return [
                                'id' => $option->id,
                                'title' => $option->title,
                                'order' => $option->order,
                            ];
                        }),
                    ];
                })->values()->all(),
                'webinars' => $webinars->values()->all(),
            ];
        });

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $payload);
    }



}
