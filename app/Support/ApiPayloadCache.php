<?php

namespace App\Support;

use Closure;
use Illuminate\Support\Facades\Cache;

class ApiPayloadCache
{
    public static function enabled(): bool
    {
        return (bool) config('api_cache.enabled', true);
    }

    public static function ttl(string $name): int
    {
        return max(0, (int) config("api_cache.ttl.{$name}", 300));
    }

    /**
     * Remember a value for guests only (course-style APIs that vary per user).
     */
    public static function rememberForGuest(string $key, string $ttlConfigKey, Closure $callback): mixed
    {
        if (!self::enabled() || apiAuth()) {
            return $callback();
        }

        $ttl = self::ttl($ttlConfigKey);
        if ($ttl <= 0) {
            return $callback();
        }

        return Cache::remember($key, now()->addSeconds($ttl), $callback);
    }

    /**
     * Remember a value for all clients (shared public config).
     */
    public static function rememberShared(string $key, string $ttlConfigKey, Closure $callback): mixed
    {
        if (!self::enabled()) {
            return $callback();
        }

        $ttl = self::ttl($ttlConfigKey);
        if ($ttl <= 0) {
            return $callback();
        }

        return Cache::remember($key, now()->addSeconds($ttl), $callback);
    }

    public static function requestFingerprint(): string
    {
        $query = request()->query();

        ksort($query);

        return md5(http_build_query($query));
    }

    public static function localeTag(): string
    {
        return app()->getLocale();
    }
}
