<?php

namespace App\CustomStorage;

use League\Flysystem\AwsS3V3\AwsS3V3Adapter;

class CustomR2Adapter extends AwsS3V3Adapter
{
    /**
     * Get the public URL for a file
     *
     * @param string $path
     * @return string
     */
    public function getUrl($path)
    {
        $publicUrl = config('r2.public_url');
        return rtrim($publicUrl, '/') . '/' . ltrim($path, '/');
    }
}
