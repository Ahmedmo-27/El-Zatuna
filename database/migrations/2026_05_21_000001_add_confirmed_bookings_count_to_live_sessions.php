<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * No-op: confirmed_bookings_count is created in 2026_05_20_182528_create_live_sessions_table.
     * Kept so environments that already ran this migration name do not re-apply a duplicate column.
     */
    public function up(): void
    {
        if (!Schema::hasTable('live_sessions') || Schema::hasColumn('live_sessions', 'confirmed_bookings_count')) {
            return;
        }

        // Legacy fallback only (older branches without the column on create).
        Schema::table('live_sessions', function (Blueprint $table) {
            $table->unsignedInteger('confirmed_bookings_count')->default(0)->after('max_students');
        });
    }

    public function down(): void
    {
        // Intentionally empty: do not drop column that belongs to the base live_sessions schema.
    }
};
