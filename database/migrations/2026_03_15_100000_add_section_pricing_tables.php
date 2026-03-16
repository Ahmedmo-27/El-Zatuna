<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('webinar_chapters', function (Blueprint $table) {
            if (!Schema::hasColumn('webinar_chapters', 'price')) {
                $table->decimal('price', 13, 2)->default(0)->after('order');
            }
        });

        if (!Schema::hasColumn('sales', 'chapter_id')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->unsignedInteger('chapter_id')->nullable()->after('file_id');
                $table->foreign('chapter_id')->references('id')->on('webinar_chapters')->onDelete('cascade');
            });
        }

        DB::statement("ALTER TABLE `sales` MODIFY COLUMN `type` enum('webinar', 'meeting', 'subscribe', 'promotion', 'registration_package', 'product', 'bundle', 'installment_payment', 'gift', 'file', 'chapter') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL AFTER `payment_method`");

        Schema::table('cart', function (Blueprint $table) {
            if (!Schema::hasColumn('cart', 'chapter_id')) {
                $table->unsignedInteger('chapter_id')->nullable()->after('file_id');
                $table->foreign('chapter_id')->references('id')->on('webinar_chapters')->onDelete('cascade');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'chapter_id')) {
                $table->unsignedInteger('chapter_id')->nullable()->after('file_id');
                $table->foreign('chapter_id')->references('id')->on('webinar_chapters')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `sales` MODIFY COLUMN `type` enum('webinar', 'meeting', 'subscribe', 'promotion', 'registration_package', 'product', 'bundle', 'installment_payment', 'gift', 'file') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL AFTER `payment_method`");

        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'chapter_id')) {
                $table->dropForeign(['chapter_id']);
                $table->dropColumn('chapter_id');
            }
        });

        Schema::table('cart', function (Blueprint $table) {
            if (Schema::hasColumn('cart', 'chapter_id')) {
                $table->dropForeign(['chapter_id']);
                $table->dropColumn('chapter_id');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'chapter_id')) {
                $table->dropForeign(['chapter_id']);
                $table->dropColumn('chapter_id');
            }
        });

        Schema::table('webinar_chapters', function (Blueprint $table) {
            if (Schema::hasColumn('webinar_chapters', 'price')) {
                $table->dropColumn('price');
            }
        });
    }
};
