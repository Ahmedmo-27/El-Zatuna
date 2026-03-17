<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscribes', function (Blueprint $table) {
            if (!Schema::hasColumn('subscribes', 'type')) {
                // Place type after usable_count (title is stored in translations table)
                $table->string('type', 64)->nullable()->after('usable_count');
            }

            if (!Schema::hasColumn('subscribes', 'scoped_to_university')) {
                $table->boolean('scoped_to_university')->default(false)->after('access_all_courses');
            }

            if (!Schema::hasColumn('subscribes', 'scoped_to_faculty')) {
                $table->boolean('scoped_to_faculty')->default(false)->after('scoped_to_university');
            }
        });
    }

    public function down(): void
    {
        Schema::table('subscribes', function (Blueprint $table) {
            if (Schema::hasColumn('subscribes', 'type')) {
                $table->dropColumn('type');
            }

            if (Schema::hasColumn('subscribes', 'scoped_to_university')) {
                $table->dropColumn('scoped_to_university');
            }

            if (Schema::hasColumn('subscribes', 'scoped_to_faculty')) {
                $table->dropColumn('scoped_to_faculty');
            }
        });
    }
};

