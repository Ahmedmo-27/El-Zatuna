<?php

namespace App\Providers;

use App\Mail\Transport\BrevoTransport;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerBrevoMailTransport();

        Paginator::defaultView('pagination::default');

        if ($this->app->runningInConsole() && class_exists(\Illuminate\Foundation\Console\ServeCommand::class)) {
            \Illuminate\Foundation\Console\ServeCommand::$passthroughVariables = array_unique(array_merge(
                \Illuminate\Foundation\Console\ServeCommand::$passthroughVariables,
                array_keys($_SERVER)
            ));
        }
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
