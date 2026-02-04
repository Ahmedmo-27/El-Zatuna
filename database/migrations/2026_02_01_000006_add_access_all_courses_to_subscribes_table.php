<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscribes', function (Blueprint $table) {
            $table->boolean('access_all_courses')->default(false)->after('infinite_use');
        });
    }

    public function down(): void
    {
        Schema::table('subscribes', function (Blueprint $table) {
            $table->dropColumn('access_all_courses');
        });
    }
};
