<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudflare R2 Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for Cloudflare R2 cloud storage.
    | R2 is S3-compatible, so we use AWS SDK with custom endpoint.
    |
    */

    'account_id' => env('R2_ACCOUNT_ID', ''),
    'access_key_id' => env('R2_ACCESS_KEY_ID', ''),
    'secret_access_key' => env('R2_SECRET_ACCESS_KEY', ''),
    'bucket' => env('R2_BUCKET', ''),
    'region' => env('R2_REGION', 'auto'),
    'endpoint' => env('R2_ENDPOINT', ''),
    'url' => env('R2_URL', ''),
    'public_url' => env('R2_PUBLIC_URL', ''),
    
    /*
    |--------------------------------------------------------------------------
    | R2 Storage Directory Structure
    |--------------------------------------------------------------------------
    |
    | The directory structure for storing course content in R2:
    | Courses/{webinar_id}/{lesson_id}/{video_id|document_id}
    |
    */
    
    'use_path_style_endpoint' => true,
    'visibility' => 'public',
    
];
