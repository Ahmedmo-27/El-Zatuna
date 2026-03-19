<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'applied_subscribe_id')) {
                $table->unsignedInteger('applied_subscribe_id')->nullable()->after('subscribe_id');
                $table->foreign('applied_subscribe_id')->references('id')->on('subscribes')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'applied_subscribe_id')) {
                $table->dropForeign(['applied_subscribe_id']);
                $table->dropColumn('applied_subscribe_id');
            }
        });
    }
};

