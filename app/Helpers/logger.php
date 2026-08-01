<?php

use App\Services\AppLogger;

if (! function_exists('app_logger')) {
    /**
     * Get the application logger instance or log at a given level.
     *
     * app_logger('Payment failed', ['order_id' => 1], 'error');
     * app_logger()->channel('payment')->info('Gateway response', $data);
     */
    function app_logger(?string $message = null, array $context = [], ?string $level = null): AppLogger
    {
        $logger = app(AppLogger::class);

        if ($message === null) {
            return $logger;
        }

        $level = $level ?? 'info';

        if (method_exists($logger, $level)) {
            $logger->{$level}($message, $context);
        }

        return $logger;
    }
}
