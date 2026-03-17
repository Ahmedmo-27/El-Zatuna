<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscribe_uses', function (Blueprint $table) {
            if (!Schema::hasColumn('subscribe_uses', 'chapter_id')) {
                $table->unsignedInteger('chapter_id')->nullable()->after('webinar_id');
                $table->foreign('chapter_id')->references('id')->on('webinar_chapters')->onDelete('cascade');
            }

            if (!Schema::hasColumn('subscribe_uses', 'item_type')) {
                $table->string('item_type', 32)->nullable()->after('chapter_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('subscribe_uses', function (Blueprint $table) {
            if (Schema::hasColumn('subscribe_uses', 'chapter_id')) {
                $table->dropForeign(['chapter_id']);
                $table->dropColumn('chapter_id');
            }

            if (Schema::hasColumn('subscribe_uses', 'item_type')) {
                $table->dropColumn('item_type');
            }
        });
    }
};

