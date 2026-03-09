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

            $response = apiResponse2(0, 'rate_limit_exceeded', 'Too many requests. Please try again later.', [
                'retry_after' => (int) $retryAfter,
                'retry_after_date' => $resetTime->toIso8601String(),
                'limit' => 300,
                'window' => '1 minute'
            ]);

            $response->setStatusCode(429);
            $response->headers->add([
                'X-RateLimit-Limit' => 300,
                'X-RateLimit-Remaining' => 0,
                'X-RateLimit-Reset' => $resetTime->timestamp,
                'Retry-After' => $retryAfter
            ]);

            return $response;
        }
    }
}
