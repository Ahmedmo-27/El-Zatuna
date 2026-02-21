<?php

namespace App\Providers;

use App\CustomStorage\CustomR2Adapter;
use Aws\S3\S3Client;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;

class R2StorageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Storage::extend('r2', function ($app, $config) {
            // Create S3 client with proper R2 configuration
            $verify = $this->getSslCertificatePath();
            $client = new S3Client([
                'credentials' => [
                    'key' => $config['key'],
                    'secret' => $config['secret'],
                ],
                'region' => $config['region'],
                'version' => 'latest',
                'bucket_endpoint' => false,
                'use_path_style_endpoint' => true,
                'endpoint' => $config['endpoint'],
                'verify' => $verify,
                'http' => [
                    'verify' => $verify,
                ],
            ]);

            // Options for the adapter (same as Minio)
            $options = [
                'override_visibility_on_copy' => true
            ];

            // Create custom R2 adapter with URL support
            $adapter = new CustomR2Adapter($client, $config['bucket'], '', null, null, $options);

            $filesystem = new Filesystem($adapter);

            return new FilesystemAdapter($filesystem, $adapter);
        });
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        //
    }
    
    /**
     * Get the SSL certificate path for R2 connections
     * 
     * We default to false (disable verification) to avoid cURL error 77 when
     * php.ini curl.cainfo or env points to a non-existent path (e.g. another project's cacert.pem).
     * 
     * @return string|bool Certificate file path, true for system store, or false to disable
     */
    protected function getSslCertificatePath()
    {
        $sslVerify = env('R2_SSL_VERIFY', 'false');
        if (strtolower($sslVerify) === 'true' || $sslVerify === true) {
            $certPath = env('R2_SSL_CERT_PATH');
            if (!empty($certPath) && file_exists($certPath)) {
                return $certPath;
            }
            return true;
        }
        return false;
    }
}
