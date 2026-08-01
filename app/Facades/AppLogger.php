<?php

namespace App\Facades;

use App\Services\AppLogger as AppLoggerService;
use App\Services\ProcessLogger;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Services\AppLogger channel(string $channel)
 * @method static \App\Services\ProcessLogger process(string $processName)
 * @method static AppLoggerService withContext(array $context)
 * @method static void emergency(string $message, array $context = [])
 * @method static void alert(string $message, array $context = [])
 * @method static void critical(string $message, array $context = [])
 * @method static void error(string $message, array $context = [])
 * @method static void warning(string $message, array $context = [])
 * @method static void notice(string $message, array $context = [])
 * @method static void info(string $message, array $context = [])
 * @method static void debug(string $message, array $context = [])
 * @method static void exception(\Throwable $e, ?string $message = null, array $context = [])
 *
 * @see AppLoggerService
 */
class AppLogger extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AppLoggerService::class;
    }
}
