<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'brevo' => [
        'key' => env('BREVO_API_KEY'),
        'verify' => env('BREVO_SSL_VERIFY', true), // set to false only if you get cURL error 77 (wrong/missing CA bundle)
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT'),
    ],
    'facebook' => [
        'client_id' => env('FACEBOOK_APP_ID'),
        'client_secret' => env('FACEBOOK_APP_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT'),
    ],

    'paytm-wallet' => [
        'env' => env('PAYTM_ENVIRONMENT'), // values : (local | production)
        'merchant_id' => env('PAYTM_MERCHANT_ID'),
        'merchant_key' => env('PAYTM_MERCHANT_KEY'),
        'merchant_website' => env('PAYTM_MERCHANT_WEBSITE'),
        'channel' => env('PAYTM_CHANNEL'),
        'industry_type' => env('PAYTM_INDUSTRY_TYPE'),
    ],

    // SMS Channel
    "msg91" => [
        'key' => '', // set from Channel
        'otp_message' => "your verification code: ##OTP##",
        'otp_length' => 5,
    ],

    // Cloudflare Worker Stream Service (for course videos in R2 under Courses/ only)
    // When STREAM_WORKER_BASE is set, getFilePath() returns worker URL with token and playFile() redirects to worker.
    // Course-Assets/ and Profile-Assets/ are never sent to the worker; they use R2_PUBLIC_URL or Laravel proxy (/r2-asset/).
    'stream' => [
        'token_secret' => env('STREAM_TOKEN_SECRET'),
        'worker_base' => env('STREAM_WORKER_BASE'),
        'token_ttl' => env('STREAM_TOKEN_TTL', 120), // Default 120 seconds (2 minutes)
    ],
];
