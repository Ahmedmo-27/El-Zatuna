<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConfirmedBookingsCountToLiveSessions extends Migration
{
    public function up()
    {
        Schema::table('live_sessions', function (Blueprint $table) {
            $table->unsignedInteger('confirmed_bookings_count')->default(0)->after('max_students');
        });
    }

    public function down()
    {
        Schema::table('live_sessions', function (Blueprint $table) {
            $table->dropColumn('confirmed_bookings_count');
        });
    }
}
