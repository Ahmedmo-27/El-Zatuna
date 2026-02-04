<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payouts', function (Blueprint $table) {
            if (!Schema::hasColumn('payouts', 'tutor_id')) {
                $table->unsignedInteger('tutor_id')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('payouts', 'paid_at')) {
                $table->integer('paid_at')->nullable()->after('created_at');
            }
        });

        DB::statement("ALTER TABLE payouts MODIFY status ENUM('waiting','done','reject','pending','paid')");

        Schema::table('payouts', function (Blueprint $table) {
            if (Schema::hasColumn('payouts', 'tutor_id')) {
                $table->foreign('tutor_id')->references('id')->on('tutors')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payouts', function (Blueprint $table) {
            if (Schema::hasColumn('payouts', 'tutor_id')) {
                $table->dropForeign(['tutor_id']);
                $table->dropColumn('tutor_id');
            }
            if (Schema::hasColumn('payouts', 'paid_at')) {
                $table->dropColumn('paid_at');
            }
        });

        DB::statement("ALTER TABLE payouts MODIFY status ENUM('waiting','done','reject')");
    }
};
