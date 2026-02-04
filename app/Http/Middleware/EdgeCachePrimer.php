<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EdgeCachePrimer
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
