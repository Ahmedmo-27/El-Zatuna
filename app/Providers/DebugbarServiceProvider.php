<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DebugbarServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Increase execution time when debugbar is enabled to prevent timeout
        if (config('debugbar.enabled') && app()->bound('debugbar')) {
            @ini_set('max_execution_time', 300);
            @set_time_limit(300);
            
            // Disable debugbar for API routes to prevent timeout
            if (request()->is('api/*')) {
                app('debugbar')->disable();
            }
            
            // Disable debugbar for AJAX requests if configured
            if (request()->ajax() && !config('debugbar.capture_ajax', true)) {
                app('debugbar')->disable();
            }
        }
    }
}
