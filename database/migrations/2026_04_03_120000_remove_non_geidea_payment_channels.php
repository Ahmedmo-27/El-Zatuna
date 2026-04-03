<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Keep only the Geidea online payment channel row(s); remove all other gateways.
     * Offline bank / manual flows are unchanged (other tables).
     */
    public function up(): void
    {
        DB::table('payment_channels')->where('class_name', '!=', 'Geidea')->delete();
    }

    public function down(): void
    {
        // Irreversible: previous gateway rows are not restored.
    }
};
