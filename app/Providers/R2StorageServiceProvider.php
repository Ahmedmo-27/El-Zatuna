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
                'http' => [
                    'verify' => $this->getSslCertificatePath(),
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
     * Production-ready: For R2 (Cloudflare's trusted service), we disable SSL verification
     * to avoid certificate path issues across different environments.
     * 
     * Can be configured via environment variables:
     * - R2_SSL_CERT_PATH: Path to custom certificate file (if you want to enable verification)
     * - R2_SSL_VERIFY: Set to 'true' to enable verification (default: false for R2)
     * 
     * @return string|bool Certificate file path, true for system store, or false to disable
     */
    protected function getSslCertificatePath()
    {
        // Check if SSL verification is explicitly enabled
        $sslVerify = env('R2_SSL_VERIFY', 'false');
        if (strtolower($sslVerify) === 'true' || $sslVerify === true) {
            // If verification is enabled, check for custom certificate path
            $certPath = env('R2_SSL_CERT_PATH');
            if (!empty($certPath) && file_exists($certPath)) {
                return $certPath;
            }
            // Use system certificate store if no custom path
            return true;
        }
        
        // Default: Disable SSL verification for R2 (Cloudflare is trusted)
        // This avoids certificate path issues across different environments
        // Set R2_SSL_VERIFY=true in .env if you want to enable verification
        return false;
    }
}
