<?php

namespace App\Providers;

use App\Mail\Transport\BrevoTransport;
use App\Services\AppLogger;
use Illuminate\Mail\MailManager;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AppLogger::class, function () {
            return new AppLogger(config('logging.default', 'stack'));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->suppressSwaggerUnknownClassWarnings();
        $this->registerBrevoMailTransport();

        Paginator::defaultView('pagination::default');

        if ($this->app->runningInConsole() && class_exists(\Illuminate\Foundation\Console\ServeCommand::class)) {
            \Illuminate\Foundation\Console\ServeCommand::$passthroughVariables = array_unique(array_merge(
                \Illuminate\Foundation\Console\ServeCommand::$passthroughVariables,
                array_keys($_SERVER)
            ));
        }
    }

    /**
     * Prevent swagger-php "Skipping unknown ..." warnings from being converted to exceptions
     * when running php artisan l5-swagger:generate.
     */
    protected function suppressSwaggerUnknownClassWarnings(): void
    {
        set_error_handler(function (int $severity, string $message, string $file, int $line) {
            if ($severity === E_USER_WARNING && str_contains($message, 'Skipping unknown ')) {
                return true; // Suppress: do not convert to ErrorException
            }
            return false; // Let other errors be handled normally
        }, E_USER_WARNING);
    }

    protected function registerBrevoMailTransport(): void
    {
        $this->app->afterResolving(MailManager::class, function (MailManager $manager) {
            $manager->extend('brevo', function () {
                $key = config('services.brevo.key') ?? config('mail.mailers.brevo.key');
                if (empty($key)) {
                    throw new \InvalidArgumentException('Brevo API key is not set. Set BREVO_API_KEY in .env and add brevo.key to config/services.php.');
                }
                return new BrevoTransport($key);
            });
        });
    }
}
