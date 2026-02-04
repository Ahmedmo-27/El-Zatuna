<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class UpdateUserAllowedDevices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:allowed-devices {userId} {devices=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the number of allowed devices for a specific user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userId = $this->argument('userId');
        $devices = (int) $this->argument('devices');

        if ($devices < 1) {
            $this->error('Allowed devices must be at least 1');
            return 1;
        }

        if ($devices > 10) {
            $this->error('Allowed devices cannot exceed 10 for security reasons');
            return 1;
        }

        $user = User::find($userId);

        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return 1;
        }

        $oldValue = $user->allowed_devices;
        $user->allowed_devices = $devices;
        $user->save();

        $this->info("User '{$user->full_name}' (ID: {$userId})");
        $this->info("Allowed devices updated: {$oldValue} → {$devices}");
        
        return 0;
    }
}
