<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

/**
 * Centralized application logger with structured context for traceability.
 *
 * Usage:
 *   app_logger()->info('Payment initiated', ['order_id' => $id]);
 *   AppLogger::channel('payment')->error('Gateway failed', ['gateway' => 'geidea']);
 *   AppLogger::process('refund')->start(['refund_id' => $id]);
 *   AppLogger::process('refund')->complete();
 *   AppLogger::exception($e, 'Upload failed', ['file_id' => $id]);
 */
class AppLogger
{
    protected string $channel;

    /** @var array<string, float> */
    protected array $processTimers = [];

    protected ?string $activeProcess = null;

    /** @var array<string, mixed> */
    protected array $persistentContext = [];

    /** Keys redacted from log context (case-insensitive partial match). */
    protected const SENSITIVE_KEYS = [
        'password',
        'password_confirmation',
        'token',
        'secret',
        'api_key',
        'apikey',
        'authorization',
        'credit_card',
        'card_number',
        'cvv',
        'ssn',
        'private_key',
    ];

    public function __construct(string $channel = 'stack')
    {
        $this->channel = $channel;
    }

    /**
     * Create a logger bound to a specific channel.
     */
    public function channel(string $channel): self
    {
        return new self($channel);
    }

    /**
     * Create a logger scoped to a named process for start/step/complete tracing.
     */
    public function process(string $processName): ProcessLogger
    {
        return new ProcessLogger(new self($this->channel), $processName);
    }

    /**
     * Attach context that will be included in every subsequent log entry.
     *
     * @param  array<string, mixed>  $context
     */
    public function withContext(array $context): self
    {
        $clone = clone $this;
        $clone->persistentContext = array_merge($clone->persistentContext, $context);

        return $clone;
    }

    public function emergency(string $message, array $context = []): void
    {
        $this->write('emergency', $message, $context);
    }

    public function alert(string $message, array $context = []): void
    {
        $this->write('alert', $message, $context);
    }

    public function critical(string $message, array $context = []): void
    {
        $this->write('critical', $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->write('error', $message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->write('warning', $message, $context);
    }

    public function notice(string $message, array $context = []): void
    {
        $this->write('notice', $message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        $this->write('info', $message, $context);
    }

    public function debug(string $message, array $context = []): void
    {
        $this->write('debug', $message, $context);
    }

    /**
     * Log an exception with sanitized stack trace (limited frames).
     *
     * @param  array<string, mixed>  $context
     */
    public function exception(Throwable $e, ?string $message = null, array $context = []): void
    {
        $this->error($message ?? $e->getMessage(), array_merge($context, $this->formatException($e)));
    }

    /**
     * Mark the start of a traceable process.
     *
     * @param  array<string, mixed>  $context
     */
    public function processStart(string $process, array $context = []): void
    {
        $this->activeProcess = $process;
        $this->processTimers[$process] = microtime(true);

        $this->info("Process started: {$process}", array_merge($context, [
            'event' => 'process_start',
            'process' => $process,
        ]));
    }

    /**
     * Log an intermediate step within an active process.
     *
     * @param  array<string, mixed>  $context
     */
    public function processStep(string $step, array $context = []): void
    {
        $this->info("Process step: {$step}", array_merge($context, [
            'event' => 'process_step',
            'process' => $this->activeProcess,
            'step' => $step,
        ]));
    }

    /**
     * Mark successful completion of a process and log elapsed time.
     *
     * @param  array<string, mixed>  $context
     */
    public function processComplete(?string $process = null, array $context = []): void
    {
        $process = $process ?? $this->activeProcess ?? 'unknown';
        $durationMs = $this->resolveProcessDurationMs($process);

        $this->info("Process completed: {$process}", array_merge($context, [
            'event' => 'process_complete',
            'process' => $process,
            'duration_ms' => $durationMs,
            'status' => 'success',
        ]));

        unset($this->processTimers[$process]);

        if ($this->activeProcess === $process) {
            $this->activeProcess = null;
        }
    }

    /**
     * Mark a process as failed and log the exception.
     *
     * @param  array<string, mixed>  $context
     */
    public function processFailed(Throwable $e, ?string $process = null, array $context = []): void
    {
        $process = $process ?? $this->activeProcess ?? 'unknown';
        $durationMs = $this->resolveProcessDurationMs($process);

        $this->error("Process failed: {$process}", array_merge($context, $this->formatException($e), [
            'event' => 'process_failed',
            'process' => $process,
            'duration_ms' => $durationMs,
            'status' => 'failed',
        ]));

        unset($this->processTimers[$process]);

        if ($this->activeProcess === $process) {
            $this->activeProcess = null;
        }
    }

    /**
     * @param  array<string, mixed>  $context
     */
    protected function write(string $level, string $message, array $context): void
    {
        Log::channel($this->channel)->{$level}($message, $this->buildContext($context));
    }

    /**
     * Build the full structured context payload for a log entry.
     *
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */
    protected function buildContext(array $context): array
    {
        $merged = array_merge(
            $this->baseContext(),
            $this->persistentContext,
            $this->sanitize($context)
        );

        return array_filter($merged, static fn ($value) => $value !== null && $value !== '');
    }

    /**
     * @return array<string, mixed>
     */
    protected function baseContext(): array
    {
        $context = [
            'trace_id' => $this->resolveTraceId(),
            'app_env' => config('app.env'),
            'channel' => $this->channel,
            'timestamp' => now()->toIso8601String(),
        ];

        if ($this->activeProcess !== null) {
            $context['process'] = $this->activeProcess;
        }

        if (app()->runningInConsole()) {
            $context['runtime'] = 'console';

            if (isset($_SERVER['argv'])) {
                $context['command'] = implode(' ', array_slice($_SERVER['argv'], 0, 3));
            }

            return $context;
        }

        $context['runtime'] = 'http';

        if (! app()->runningUnitTests() && app()->bound('request')) {
            $request = request();

            $context['method'] = $request->method();
            $context['url'] = $request->fullUrl();
            $context['path'] = $request->path();
            $context['ip'] = $request->ip();
            $context['user_agent'] = Str::limit((string) $request->userAgent(), 200);

            if ($request->route()?->getName()) {
                $context['route'] = $request->route()->getName();
            }
        }

        if (Auth::check()) {
            $context['user_id'] = Auth::id();
        }

        return $context;
    }

    protected function resolveTraceId(): string
    {
        if (app()->bound('log.trace_id')) {
            return (string) app('log.trace_id');
        }

        $traceId = null;

        if (! app()->runningInConsole() && app()->bound('request')) {
            $request = request();
            $traceId = $request->header('X-Request-ID')
                ?? $request->header('X-Correlation-ID')
                ?? $request->header('X-Trace-ID');
        }

        $traceId = $traceId ?: (string) Str::uuid();

        app()->instance('log.trace_id', $traceId);

        return $traceId;
    }

    protected function resolveProcessDurationMs(string $process): ?float
    {
        if (! isset($this->processTimers[$process])) {
            return null;
        }

        return round((microtime(true) - $this->processTimers[$process]) * 1000, 2);
    }

    /**
     * @return array<string, mixed>
     */
    protected function formatException(Throwable $e): array
    {
        return [
            'exception' => get_class($e),
            'exception_message' => $e->getMessage(),
            'exception_code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $this->formatTrace($e),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    protected function formatTrace(Throwable $e): array
    {
        $limit = (int) config('logging.trace_limit', 15);

        return collect($e->getTrace())
            ->take($limit)
            ->map(static function (array $frame): array {
                return array_filter([
                    'file' => $frame['file'] ?? null,
                    'line' => $frame['line'] ?? null,
                    'function' => trim(($frame['class'] ?? '') . ($frame['type'] ?? '') . ($frame['function'] ?? '')),
                ]);
            })
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */
    protected function sanitize(array $context): array
    {
        $sanitized = [];

        foreach ($context as $key => $value) {
            if ($this->isSensitiveKey((string) $key)) {
                $sanitized[$key] = '[REDACTED]';
                continue;
            }

            if (is_array($value)) {
                $sanitized[$key] = $this->sanitize($value);
                continue;
            }

            $sanitized[$key] = $value;
        }

        return $sanitized;
    }

    protected function isSensitiveKey(string $key): bool
    {
        $normalized = strtolower($key);

        foreach (self::SENSITIVE_KEYS as $sensitive) {
            if (str_contains($normalized, $sensitive)) {
                return true;
            }
        }

        return false;
    }
}

/**
 * Fluent wrapper for process-scoped logging.
 */
class ProcessLogger
{
    public function __construct(
        protected AppLogger $logger,
        protected string $processName
    ) {}

    /**
     * @param  array<string, mixed>  $context
     */
    public function start(array $context = []): AppLogger
    {
        $this->logger->processStart($this->processName, $context);

        return $this->logger;
    }

    /**
     * @param  array<string, mixed>  $context
     */
    public function step(string $step, array $context = []): AppLogger
    {
        $this->logger->processStep($step, $context);

        return $this->logger;
    }

    /**
     * @param  array<string, mixed>  $context
     */
    public function complete(array $context = []): AppLogger
    {
        $this->logger->processComplete($this->processName, $context);

        return $this->logger;
    }

    /**
     * @param  array<string, mixed>  $context
     */
    public function failed(Throwable $e, array $context = []): AppLogger
    {
        $this->logger->processFailed($e, $this->processName, $context);

        return $this->logger;
    }
}
