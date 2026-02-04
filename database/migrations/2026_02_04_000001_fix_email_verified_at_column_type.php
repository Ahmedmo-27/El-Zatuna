<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change email_verified_at from DATETIME to INT (UNIX timestamp)
        // to match User model's $dateFormat = 'U'
        DB::statement('ALTER TABLE users MODIFY COLUMN email_verified_at INT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to TIMESTAMP
        DB::statement('ALTER TABLE users MODIFY COLUMN email_verified_at TIMESTAMP NULL');
    }
};
