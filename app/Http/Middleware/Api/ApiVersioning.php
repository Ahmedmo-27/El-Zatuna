<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;

class ApiVersioning
{
    /**
     * API versions configuration
     */
    protected $versions = [
        'development' => [
            'status' => 'active',
            'deprecated' => false,
            'sunset_date' => null,
            'successor' => 'v1',
        ],
        'v1' => [
            'status' => 'active',
            'deprecated' => false,
            'sunset_date' => null,
            'successor' => null,
        ],
    ];

    /**
     * Deprecated endpoints configuration
     */
    protected $deprecatedEndpoints = [
        // Example: Old endpoints that will be removed
        // 'GET /api/development/panel/webinars/purchases' => [
        //     'sunset_date' => '2026-06-01',
        //     'replacement' => 'GET /api/v1/panel/courses/purchases',
        // ],
    ];

    /**
     * Handle an incoming request and add versioning headers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Extract version from URL path
        $version = $this->extractVersion($request);
        
        // Add API version header
        $response->headers->set('X-API-Version', $version);
        
        // Check if version is deprecated
        if ($this->isVersionDeprecated($version)) {
            $versionConfig = $this->versions[$version] ?? [];
            $this->addDeprecationHeaders($response, $versionConfig);
        }
        
        // Check if specific endpoint is deprecated
        $endpoint = $request->method() . ' ' . $request->path();
        if (isset($this->deprecatedEndpoints[$endpoint])) {
            $this->addEndpointDeprecationHeaders($response, $this->deprecatedEndpoints[$endpoint]);
        }

        return $response;
    }

    /**
     * Extract API version from request path
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function extractVersion(Request $request)
    {
        $path = $request->path();
        
        // Match /api/{version}/...
        if (preg_match('#^api/([^/]+)/#', $path, $matches)) {
            return $matches[1];
        }
        
        return 'development';
    }

    /**
     * Check if version is deprecated
     *
     * @param  string  $version
     * @return bool
     */
    protected function isVersionDeprecated($version)
    {
        return isset($this->versions[$version]) && 
               ($this->versions[$version]['deprecated'] ?? false);
    }

    /**
     * Add deprecation headers to response
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @param  array  $config
     * @return void
     */
    protected function addDeprecationHeaders($response, $config)
    {
        // Add Deprecation header (RFC 8594)
        $deprecationValue = 'version="' . ($config['version'] ?? 'unknown') . '"';
        
        if (!empty($config['sunset_date'])) {
            $deprecationValue .= '; sunset="' . $config['sunset_date'] . '"';
        }
        
        $response->headers->set('Deprecation', $deprecationValue);
        
        // Add Sunset header (RFC 8594)
        if (!empty($config['sunset_date'])) {
            $sunsetDate = \Carbon\Carbon::parse($config['sunset_date']);
            $response->headers->set('Sunset', $sunsetDate->toRfc7231String());
        }
        
        // Add Link header to successor version
        if (!empty($config['successor'])) {
            $response->headers->set('Link', '</api/' . $config['successor'] . '/docs>; rel="successor-version"');
        }
    }

    /**
     * Add endpoint-specific deprecation headers
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @param  array  $config
     * @return void
     */
    protected function addEndpointDeprecationHeaders($response, $config)
    {
        $response->headers->set('Deprecation', 'true');
        
        if (!empty($config['sunset_date'])) {
            $sunsetDate = \Carbon\Carbon::parse($config['sunset_date']);
            $response->headers->set('Sunset', $sunsetDate->toRfc7231String());
        }
        
        if (!empty($config['replacement'])) {
            $response->headers->set('X-Deprecated-Replacement', $config['replacement']);
        }
        
        // Add warning message to response data if JSON
        if ($response->headers->get('Content-Type') === 'application/json') {
            $content = json_decode($response->getContent(), true);
            
            if (is_array($content)) {
                $content['_deprecated'] = [
                    'warning' => 'This endpoint is deprecated and will be removed',
                    'sunset_date' => $config['sunset_date'] ?? null,
                    'replacement' => $config['replacement'] ?? null,
                ];
                
                $response->setContent(json_encode($content));
            }
        }
    }
}
