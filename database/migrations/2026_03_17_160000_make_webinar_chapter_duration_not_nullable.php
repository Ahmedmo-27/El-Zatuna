<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure no NULL durations remain before making the column non-nullable
        DB::table('webinar_chapters')
            ->whereNull('duration')
            ->update(['duration' => 0]);

        Schema::table('webinar_chapters', function (Blueprint $table) {
            // Change duration to be NOT NULL with a default of 0 minutes
            DB::statement("ALTER TABLE `webinar_chapters` MODIFY COLUMN `duration` INT UNSIGNED NOT NULL DEFAULT 0");
        });
    }

    public function down(): void
    {
        Schema::table('webinar_chapters', function (Blueprint $table) {
            DB::statement("ALTER TABLE `webinar_chapters` MODIFY COLUMN `duration` INT UNSIGNED NULL DEFAULT NULL");
        });
    }
};

