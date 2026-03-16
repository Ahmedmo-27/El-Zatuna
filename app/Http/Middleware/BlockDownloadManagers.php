<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockDownloadManagers
{
    /**
     * List of known download manager user agents and patterns
     */
    protected $downloadManagerPatterns = [
        'IDM', 'Internet Download Manager', 'InternetDownloadManager',
        'Wget', 'curl', 'aria2', 'axel', 'lftp',
        'JDownloader', 'Free Download Manager', 'FDM',
        'Orbit Downloader', 'FlashGet', 'GetRight',
        'Download Accelerator', 'Go!Zilla', 'ReGet',
        'Mass Downloader', 'NetAnts', 'Star Downloader',
        'Download Express', 'Fresh Download', 'GetWeb',
        'HiDownload', 'iGetter', 'LeechGet',
        'Offline Explorer', 'SiteSnagger', 'Teleport',
        'WebCopier', 'WebReaper', 'WebStripper',
        'httrack', 'wget', 'curl', 'aria2c',
        'python-requests', 'scrapy', 'grab',
        'HTTPie', 'Postman', 'Insomnia',
        'okhttp', 'Apache-HttpClient', 'Java',
        'libwww-perl', 'WWW-Mechanize',
    ];

    /**
     * Suspicious headers that download managers often use
     */
    protected $suspiciousHeaders = [
        'X-Requested-With' => ['XMLHttpRequest'], // Some downloaders use this
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $userAgent = $request->userAgent() ?? '';
        $userAgentLower = strtolower($userAgent);

        // Check for download manager user agents
        foreach ($this->downloadManagerPatterns as $pattern) {
            if (stripos($userAgentLower, strtolower($pattern)) !== false) {
                \Log::warning('Download manager blocked', [
                    'ip' => $request->ip(),
                    'user_agent' => $userAgent,
                    'pattern' => $pattern,
                    'url' => $request->fullUrl(),
                ]);

                abort(403, 'Access denied: Download managers are not allowed');
            }
        }

        // Check for suspicious request patterns
        // Download managers often don't send proper browser headers
        $hasAcceptHeader = $request->hasHeader('Accept');
        $hasAcceptLanguage = $request->hasHeader('Accept-Language');
        $hasAcceptEncoding = $request->hasHeader('Accept-Encoding');
        
        // If missing critical browser headers, it might be a downloader
        if (!$hasAcceptHeader || (!$hasAcceptLanguage && !$hasAcceptEncoding)) {
            // But allow if it's a range request (video seeking)
            if (!$request->hasHeader('Range')) {
                \Log::warning('Suspicious request - missing browser headers', [
                    'ip' => $request->ip(),
                    'user_agent' => $userAgent,
                    'url' => $request->fullUrl(),
                    'headers' => $request->headers->all(),
                ]);
            }
        }

        // Check for suspicious connection patterns
        // Download managers often make rapid sequential requests
        $cacheKey = 'video_request:' . $request->ip() . ':' . $request->path();
        $requestCount = \Cache::get($cacheKey, 0);
        
        if ($requestCount > 10) { // More than 10 requests in 1 second is suspicious
            \Log::warning('Suspicious rapid requests detected', [
                'ip' => $request->ip(),
                'count' => $requestCount,
                'user_agent' => $userAgent,
                'url' => $request->fullUrl(),
            ]);
            
            // Don't block, but log it - might be legitimate video seeking
        }
        
        \Cache::put($cacheKey, $requestCount + 1, 1); // 1 second window

        return $next($request);
    }
}






