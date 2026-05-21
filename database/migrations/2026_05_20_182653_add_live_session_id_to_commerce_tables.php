<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->addLiveSessionIdColumn('cart');
        $this->addLiveSessionIdColumn('order_items');
        $this->addLiveSessionIdColumn('sales');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->dropLiveSessionIdColumn('cart');
        $this->dropLiveSessionIdColumn('order_items');
        $this->dropLiveSessionIdColumn('sales');
    }

    private function addLiveSessionIdColumn(string $tableName): void
    {
        if (Schema::hasColumn($tableName, 'live_session_id')) {
            return;
        }

        $afterColumn = $this->resolveAfterColumn($tableName);

        Schema::table($tableName, function (Blueprint $table) use ($afterColumn) {
            if ($afterColumn) {
                $table->unsignedBigInteger('live_session_id')->nullable()->after($afterColumn);
            } else {
                $table->unsignedBigInteger('live_session_id')->nullable();
            }
        });
    }

    private function dropLiveSessionIdColumn(string $tableName): void
    {
        if (!Schema::hasColumn($tableName, 'live_session_id')) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) {
            $table->dropColumn('live_session_id');
        });
    }

    /**
     * cart/order_items use reserve_meeting_id (meeting_id was renamed); sales may still have meeting_id.
     */
    private function resolveAfterColumn(string $tableName): ?string
    {
        foreach (['chapter_id', 'file_id', 'reserve_meeting_id', 'meeting_id', 'webinar_id'] as $column) {
            if (Schema::hasColumn($tableName, $column)) {
                return $column;
            }
        }

        return null;
    }
};
