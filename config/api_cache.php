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
    ],

];
