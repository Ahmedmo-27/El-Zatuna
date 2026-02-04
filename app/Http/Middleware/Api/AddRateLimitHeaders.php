<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class AddRateLimitHeaders
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request and add rate limit headers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Get rate limit key (similar to throttle middleware)
        $key = $this->resolveRequestSignature($request);
        
        // Default limits (can be configured)
        $maxAttempts = 300; // 300 requests per minute
        $decayMinutes = 1;
        
        // Calculate remaining attempts
        $attempts = $this->limiter->attempts($key);
        $remaining = max(0, $maxAttempts - $attempts);
        
        // Calculate reset timestamp
        $retryAfter = $this->limiter->availableIn($key);
        $resetTime = now()->addSeconds($retryAfter)->timestamp;
        
        // Add headers to response
        $response->headers->set('X-RateLimit-Limit', $maxAttempts);
        $response->headers->set('X-RateLimit-Remaining', $remaining);
        $response->headers->set('X-RateLimit-Reset', $resetTime);
        
        // If this is a 429 response, add Retry-After header
        if ($response->getStatusCode() === 429) {
            $response->headers->set('Retry-After', $retryAfter);
        }

        return $response;
    }

    /**
     * Resolve request signature for rate limiting.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function resolveRequestSignature(Request $request)
    {
        if ($user = $request->user('api')) {
            return sha1($user->id);
        }

        return sha1($request->ip());
    }
}
