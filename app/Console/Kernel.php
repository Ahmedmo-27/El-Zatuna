<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Clean up expired registration verification tokens daily
        $schedule->call(function () {
            \App\Models\RegistrationVerificationToken::cleanupExpired();
        })->daily();

        // Sync user session counts and cleanup inactive sessions daily
        $schedule->command('sessions:sync-counts --cleanup --inactive-minutes=120')
            ->daily()
            ->at('02:00');

        // Clean up debugbar storage files to prevent performance issues
        $schedule->call(function () {
            $debugbarPath = storage_path('debugbar');
            if (is_dir($debugbarPath)) {
                $files = glob($debugbarPath . '/*');
                $now = time();
                foreach ($files as $file) {
                    // Delete files older than 7 days
                    if (is_file($file) && ($now - filemtime($file) > 7 * 24 * 3600)) {
                        @unlink($file);
                    }
                }
            }
        })->daily()->at('03:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
