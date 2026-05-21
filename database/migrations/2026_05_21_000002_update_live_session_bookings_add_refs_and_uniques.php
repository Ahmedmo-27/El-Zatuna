<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateLiveSessionBookingsAddRefsAndUniques extends Migration
{
    public function up()
    {
        Schema::table('live_session_bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('live_session_bookings', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('sale_id');
            }

            if (!Schema::hasColumn('live_session_bookings', 'refund_reference')) {
                $table->string('refund_reference')->nullable()->after('payment_reference');
            }

            if (!Schema::hasColumn('live_session_bookings', 'gateway_name')) {
                $table->string('gateway_name')->nullable()->after('refund_reference');
            }

            if (!Schema::hasColumn('live_session_bookings', 'gateway_event_id')) {
                $table->string('gateway_event_id')->nullable()->after('gateway_name');
            }

            if (!Schema::hasColumn('live_session_bookings', 'refund_status')) {
                $table->enum('refund_status', ['pending','processed','failed'])->default('pending')->after('refund_reference');
            }

            $indexName = 'live_session_bookings_gateway_name_gateway_event_id_unique';

            if (!$this->hasIndex('live_session_bookings', $indexName)) {
                $table->unique(['gateway_name', 'gateway_event_id'], $indexName);
            }
        });
    }

    public function down()
    {
        Schema::table('live_session_bookings', function (Blueprint $table) {
            $indexName = 'live_session_bookings_gateway_name_gateway_event_id_unique';

            if ($this->hasIndex('live_session_bookings', $indexName)) {
                $table->dropUnique($indexName);
            }

            $columns = [];

            foreach (['payment_reference', 'refund_reference', 'gateway_name', 'gateway_event_id', 'refund_status'] as $column) {
                if (Schema::hasColumn('live_session_bookings', $column)) {
                    $columns[] = $column;
                }
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }

    private function hasIndex(string $tableName, string $indexName): bool
    {
        $result = DB::selectOne(
            'SELECT COUNT(*) AS aggregate FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = ? AND index_name = ?',
            [$tableName, $indexName]
        );

        return ((int) ($result->aggregate ?? 0)) > 0;
    }
}
