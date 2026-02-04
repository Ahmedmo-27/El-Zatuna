<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\SecurityHeaders::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\UserLocale::class,
        ],

        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':300,1', // 300 requests per minute
            \App\Http\Middleware\Api\HandleRateLimitExceeded::class,
            \App\Http\Middleware\Api\AddRateLimitHeaders::class,
            \App\Http\Middleware\Api\ApiVersioning::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of class names to conveniently assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'admin.auth' => \App\Http\Middleware\AdminAuthenticate::class,
        'panel.auth' => \App\Http\Middleware\PanelAuthenticate::class,
        'panel' => \App\Http\Middleware\PanelAuthenticate::class,
        'web.auth' => \App\Http\Middleware\WebAuthenticate::class,
        'impersonate' => \App\Http\Middleware\Impersonate::class,
        'user.not.access' => \App\Http\Middleware\UserNotAccess::class,
        'check.restriction' => \App\Http\Middleware\CheckRestriction::class,
        'check_restriction' => \App\Http\Middleware\CheckRestriction::class,
        'check.maintenance' => \App\Http\Middleware\CheckMaintenance::class,
        'check_maintenance' => \App\Http\Middleware\CheckMaintenance::class,
        'check.mobile.app' => \App\Http\Middleware\CheckMobileApp::class,
        'check_mobile_app' => \App\Http\Middleware\CheckMobileApp::class,
        'session.validity' => \App\Http\Middleware\SessionValidity::class,
        'admin.locale' => \App\Http\Middleware\AdminLocale::class,
        'edge.cache.primer' => \App\Http\Middleware\EdgeCachePrimer::class,
        'share' => \App\Http\Middleware\Share::class,
        'user.locale' => \App\Http\Middleware\UserLocale::class,
        'debugbar' => \App\Http\Middleware\DebugBar::class,
        'security.headers' => \App\Http\Middleware\SecurityHeaders::class,
        'x_frame_headers' => \App\Http\Middleware\SecurityHeaders::class,
        // API Middleware
        'api.auth' => \App\Http\Middleware\Api\Authenticate::class,
        'api.guest' => \App\Http\Middleware\Api\RedirectIfAuthenticated::class,
        'api.request.type' => \App\Http\Middleware\Api\RequestType::class,
        'api.level-access' => \App\Http\Middleware\Api\LevelAccess::class,
        'api.check.restriction' => \App\Http\Middleware\Api\CheckRestrictionAPI::class,
        'api.check.maintenance' => \App\Http\Middleware\Api\CheckMaintenance::class,
        'api.locale' => \App\Http\Middleware\Api\SetLocale::class,
        'api.check.key' => \App\Http\Middleware\Api\CheckApiKey::class,
        'api.rate-limit-headers' => \App\Http\Middleware\Api\AddRateLimitHeaders::class,
        'api.versioning' => \App\Http\Middleware\Api\ApiVersioning::class,
        'api.version' => \App\Http\Middleware\ApiVersion::class,
    ];
}
