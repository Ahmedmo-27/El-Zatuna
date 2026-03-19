<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Make webinar_chapters.duration nullable again (duration is derived from files).
        Schema::table('webinar_chapters', function (Blueprint $table) {
            DB::statement("ALTER TABLE `webinar_chapters` MODIFY COLUMN `duration` INT UNSIGNED NULL DEFAULT NULL");
        });

        // 2) Ensure file durations are always set (non-null).
        DB::table('files')
            ->whereNull('duration')
            ->update(['duration' => 0]);

        Schema::table('files', function (Blueprint $table) {
            DB::statement("ALTER TABLE `files` MODIFY COLUMN `duration` INT UNSIGNED NOT NULL DEFAULT 0");
        });
    }

    public function down(): void
    {
        // Revert: make files.duration nullable again and webinar_chapters.duration non-nullable.
        Schema::table('files', function (Blueprint $table) {
            DB::statement("ALTER TABLE `files` MODIFY COLUMN `duration` INT UNSIGNED NULL DEFAULT NULL");
        });

        Schema::table('webinar_chapters', function (Blueprint $table) {
            DB::statement("ALTER TABLE `webinar_chapters` MODIFY COLUMN `duration` INT UNSIGNED NOT NULL DEFAULT 0");
        });
    }
};

