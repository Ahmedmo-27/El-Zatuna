<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class HandleRateLimitExceeded
{
    /**
     * Handle an incoming request and format 429 responses.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (TooManyRequestsHttpException $exception) {
            $retryAfter = $exception->getHeaders()['Retry-After'] ?? 60;
            $resetTime = now()->addSeconds($retryAfter);
            
            return response()->json([
                'status' => 0,
                'code' => 'rate_limit_exceeded',
                'message' => 'Too many requests. Please try again later.',
                'data' => [
                    'retry_after' => (int) $retryAfter,
                    'retry_after_date' => $resetTime->toIso8601String(),
                    'limit' => 300,
                    'window' => '1 minute'
                ]
            ], 429, [
                'X-RateLimit-Limit' => 300,
                'X-RateLimit-Remaining' => 0,
                'X-RateLimit-Reset' => $resetTime->timestamp,
                'Retry-After' => $retryAfter
            ]);
        }
    }
}
