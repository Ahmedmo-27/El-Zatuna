<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddMeetingIdToReserveMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('reserve_meetings', 'meeting_id')) {
            Schema::table('reserve_meetings', function (Blueprint $table) {
                $table->integer('meeting_id')->unsigned()->nullable()->after('meeting_time_id');
                $table->index('meeting_id');
            });

            DB::statement("UPDATE reserve_meetings rm JOIN meeting_times mt ON rm.meeting_time_id = mt.id SET rm.meeting_id = mt.meeting_id WHERE rm.meeting_id IS NULL");

            Schema::table('reserve_meetings', function (Blueprint $table) {
                $table->foreign('meeting_id')->references('id')->on('meetings')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('reserve_meetings', 'meeting_id')) {
            Schema::table('reserve_meetings', function (Blueprint $table) {
                $table->dropForeign(['meeting_id']);
                $table->dropColumn('meeting_id');
            });
        }
    }
}
