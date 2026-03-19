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
        // Add 'r2' to the storage ENUM column
        DB::statement("ALTER TABLE `files` MODIFY COLUMN `storage` ENUM('upload', 'youtube', 'vimeo', 'external_link', 'google_drive', 'dropbox', 's3', 'iframe', 'secure_host', 'r2') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'r2' from the storage ENUM column
        DB::statement("ALTER TABLE `files` MODIFY COLUMN `storage` ENUM('upload', 'youtube', 'vimeo', 'external_link', 'google_drive', 'dropbox', 's3', 'iframe', 'secure_host') NULL");
    }
};
