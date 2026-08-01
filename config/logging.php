<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Stack trace frame limit for AppLogger exception context
    |--------------------------------------------------------------------------
    */
    'trace_limit' => (int) env('LOG_TRACE_LIMIT', 15),

    /*
    |--------------------------------------------------------------------------
    | Deprecations Log Channel
    |--------------------------------------------------------------------------
    |
    | This option controls the log channel that should be used to log warnings
    | regarding deprecated PHP and library features. This allows you to get
    | your application ready for upcoming major versions of dependencies.
    |
    */

    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
        'trace' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily', 'errors'],
            'ignore_exceptions' => false,
        ],

        'errors' => [
            'driver' => 'daily',
            'path' => storage_path('logs/errors.log'),
            'level' => 'error',
            'days' => 30,
        ],

        'app' => [
            'driver' => 'daily',
            'path' => storage_path('logs/app.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => env('LOG_LEVEL', 'critical'),
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => env('LOG_PAPERTRAIL_HANDLER', SyslogUdpHandler::class),
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
                'connectionString' => 'tls://' . env('PAPERTRAIL_URL') . ':' . env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],

        'PayTabs' => [
            'driver' => 'single',
            'path' => storage_path('logs/paytabs.log'),
            'level' => 'info',
        ],

        'ClickPay' => [
            'driver' => 'single',
            'path' => storage_path('logs/clickpay.log'),
            'level' => 'info',
        ],
        'brevo' => [
            'driver' => 'single',
            'path' => storage_path('logs/brevo_webhooks.log'),
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'payment' => [
            'driver' => 'daily',
            'path' => storage_path('logs/payment.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 30,
        ],

        'r2' => [
            'driver' => 'daily',
            'path' => storage_path('logs/r2.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
        ],

        'api' => [
            'driver' => 'daily',
            'path' => storage_path('logs/api.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
        ],

        'auth' => [
            'driver' => 'daily',
            'path' => storage_path('logs/auth.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 30,
        ],

        'queue' => [
            'driver' => 'daily',
            'path' => storage_path('logs/queue.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
        ],
    ],

];
