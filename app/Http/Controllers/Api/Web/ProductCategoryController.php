<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\ProductCategoryResource;
use App\Models\ProductCategory;
use App\Support\ApiPayloadCache;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $cacheKey = 'api:v1:product_categories:' . ApiPayloadCache::localeTag();
        $payload = ApiPayloadCache::rememberShared($cacheKey, 'product_categories', function () {
            $categories = ProductCategory::whereNull('parent_id')
                ->with([
                    'subCategories' => function ($query) {
                        $query->orderBy('order', 'asc');
                    },
                ])
                ->get();

            return [
                'categories' => ProductCategoryResource::collection($categories)->resolve(),
            ];
        });

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $payload);
    }
}
