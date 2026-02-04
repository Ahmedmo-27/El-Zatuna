<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SetDefaultAllowedDevicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update all existing users to have allowed_devices = 1 (default value)
        // This ensures backward compatibility after adding the new feature
        DB::table('users')
            ->whereNull('allowed_devices')
            ->orWhere('allowed_devices', 0)
            ->update(['allowed_devices' => 1]);
        
        $this->command->info('Default allowed_devices value set for all existing users.');
    }
}
