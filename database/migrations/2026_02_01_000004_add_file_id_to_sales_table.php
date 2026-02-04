<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->unsignedInteger('file_id')->nullable()->after('webinar_id');
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
        });

        DB::statement("ALTER TABLE `sales` MODIFY COLUMN `type` enum('webinar', 'meeting', 'subscribe', 'promotion', 'registration_package', 'product', 'bundle', 'installment_payment', 'gift', 'file') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL AFTER `payment_method`");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `sales` MODIFY COLUMN `type` enum('webinar', 'meeting', 'subscribe', 'promotion', 'registration_package', 'product', 'bundle', 'installment_payment', 'gift') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL AFTER `payment_method`");

        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['file_id']);
            $table->dropColumn('file_id');
        });
    }
};
