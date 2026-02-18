<?php

namespace App\CustomStorage;

use League\Flysystem\AwsS3V3\AwsS3V3Adapter;

class CustomR2Adapter extends AwsS3V3Adapter
{
    /**
     * Get the worker/proxy URL for a file
     * Note: Videos should use worker tokens via WebinarController, not this method
     *
     * @param string $path
     * @return string
     */
    public function getUrl($path)
    {
        // Prefer worker base URL if configured
        $workerBase = config('services.stream.worker_base');
        if (!empty($workerBase)) {
            return rtrim($workerBase, '/') . '/' . ltrim($path, '/');
        }
        
        // Fallback to public URL if worker not configured
        $publicUrl = config('r2.public_url');
        if (!empty($publicUrl)) {
            return rtrim($publicUrl, '/') . '/' . ltrim($path, '/');
        }
        
        // If neither configured, return path only
        return '/' . ltrim($path, '/');
    }
}
