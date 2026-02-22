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
        $path = ltrim($path, '/');

        // Course-Assets and Profile-Assets must NOT use the stream worker.
        // The worker is for course video streaming (Courses/...) only.
        if (str_starts_with($path, 'Course-Assets/') || str_starts_with($path, 'Profile-Assets/')) {
            $publicUrl = config('r2.public_url');
            if (!empty($publicUrl)) {
                return rtrim($publicUrl, '/') . '/' . $path;
            }
            return url('/r2-asset/' . $path);
        }

        // For course content (Courses/...): prefer worker base if configured (video streaming)
        $workerBase = config('services.stream.worker_base');
        if (!empty($workerBase)) {
            return rtrim($workerBase, '/') . '/' . $path;
        }

        $publicUrl = config('r2.public_url');
        if (!empty($publicUrl)) {
            return rtrim($publicUrl, '/') . '/' . $path;
        }

        return '/' . $path;
    }
}
