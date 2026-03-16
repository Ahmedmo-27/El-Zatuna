<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SwaggerAdminOnly
{
    /**
     * Allow only admins to access Swagger docs.
     * Not logged in → redirect to regular login (/login).
     * Logged in but not admin → 403 with message "You need admin privileges".
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        if (! auth()->user()->isAdmin() && ! app()->environment('local')) {
            abort(403, 'You need admin privileges to access the API documentation.');
        }

        return $next($request);
    }
}
