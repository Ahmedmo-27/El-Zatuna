<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Public API response caching
    |--------------------------------------------------------------------------
    |
    | Course list/detail payloads depend on the authenticated user (purchase
    | state, favorites, progress). Caching is applied only for guests unless
    | noted otherwise. Config endpoints are shared for all clients.
    |
    */

    'enabled' => env('API_CACHE_ENABLED', true),

    'ttl' => [
        'courses_index' => (int) env('API_CACHE_COURSES_INDEX_TTL', 120),
        'courses_show_guest' => (int) env('API_CACHE_COURSES_SHOW_TTL', 180),
        'courses_content_guest' => (int) env('API_CACHE_COURSES_CONTENT_TTL', 120),
        'courses_quizzes' => (int) env('API_CACHE_COURSES_QUIZZES_TTL', 300),
        'courses_certificates' => (int) env('API_CACHE_COURSES_CERTIFICATES_TTL', 300),
        'featured_courses' => (int) env('API_CACHE_FEATURED_COURSES_TTL', 120),
        'config_list' => (int) env('API_CACHE_CONFIG_LIST_TTL', 300),
        'config_register' => (int) env('API_CACHE_CONFIG_REGISTER_TTL', 300),
        'categories_index' => (int) env('API_CACHE_CATEGORIES_INDEX_TTL', 180),
        'categories_trend' => (int) env('API_CACHE_CATEGORIES_TREND_TTL', 180),
        'categories_webinars' => (int) env('API_CACHE_CATEGORIES_WEBINARS_TTL', 120),

        'regions_countries' => (int) env('API_CACHE_REGIONS_COUNTRIES_TTL', 3600),
        'regions_provinces' => (int) env('API_CACHE_REGIONS_PROVINCES_TTL', 3600),
        'regions_cities' => (int) env('API_CACHE_REGIONS_CITIES_TTL', 3600),
        'regions_districts' => (int) env('API_CACHE_REGIONS_DISTRICTS_TTL', 3600),
        'regions_countries_code' => (int) env('API_CACHE_REGIONS_COUNTRIES_CODE_TTL', 86400),
        'timezones' => (int) env('API_CACHE_TIMEZONES_TTL', 86400),
        'product_categories' => (int) env('API_CACHE_PRODUCT_CATEGORIES_TTL', 600),
        'report_reasons' => (int) env('API_CACHE_REPORT_REASONS_TTL', 1800),
        'currency_list' => (int) env('API_CACHE_CURRENCY_LIST_TTL', 3600),
    ],

];
