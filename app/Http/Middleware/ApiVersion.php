<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiVersion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $version
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $version = null)
    {
        // Check Accept header for version
        $acceptHeader = $request->header('Accept');
        
        // Parse version from Accept header (e.g., application/vnd.lms.v1+json)
        if ($acceptHeader && preg_match('/application\/vnd\.lms\.v(\d+)\+json/', $acceptHeader, $matches)) {
            $requestedVersion = 'v' . $matches[1];
            
            // If version is specified in middleware parameter, validate it matches
            if ($version && $requestedVersion !== $version) {
                return response()->json([
                    'status' => 0,
                    'code' => 'version_mismatch',
                    'message' => "API version mismatch. Requested: {$requestedVersion}, Expected: {$version}",
                    'supported_versions' => ['v1', 'v2'],
                ], 400);
            }
        }
        
        // Add API version header to response
        $response = $next($request);
        
        if ($version) {
            $response->headers->set('X-API-Version', $version);
        }
        
        return $response;
    }
    
    /**
     * Add deprecation headers to response
     *
     * @param  \Illuminate\Http\Response  $response
     * @param  string  $deprecatedVersion
     * @param  string  $sunsetDate
     * @param  string  $successorVersion
     * @return \Illuminate\Http\Response
     */
    public static function addDeprecationHeaders($response, $deprecatedVersion, $sunsetDate, $successorVersion = 'v2')
    {
        $response->headers->set('Deprecation', 'version="' . $deprecatedVersion . '"; sunset="' . $sunsetDate . '"');
        $response->headers->set('Sunset', date('r', strtotime($sunsetDate)));
        $response->headers->set('Link', '</api/' . $successorVersion . '/docs>; rel="successor-version"');
        
        return $response;
    }
}
