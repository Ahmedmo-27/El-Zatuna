<?php

use App\Enums\MorphTypesEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // One-time cleanup: reset all webinar visit counters.
        DB::table('visits_logs')
            ->where('targetable_type', MorphTypesEnum::WEBINAR)
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback for deleted analytics rows.
    }
};
