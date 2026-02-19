<?php

namespace App\Http\Middleware;

use Closure;

class IncreaseUploadLimits
{
    public function handle($request, Closure $next)
    {
        if (function_exists('ini_set')) {
            @ini_set('upload_max_filesize', '256M');
            @ini_set('post_max_size', '256M');
            @ini_set('max_execution_time', '300');
            @ini_set('max_input_time', '300');
            @ini_set('memory_limit', '512M');
        }

        return $next($request);
    }
}
