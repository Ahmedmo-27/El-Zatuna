<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class BackfillConfirmedBookingsCount extends Migration
{
    public function up()
    {
        // Backfill confirmed_bookings_count from existing paid bookings
        $rows = DB::table('live_session_bookings')
            ->select('live_session_id', DB::raw('COUNT(*) as cnt'))
            ->where('status', 'paid')
            ->groupBy('live_session_id')
            ->get();

        foreach ($rows as $r) {
            DB::table('live_sessions')->where('id', $r->live_session_id)->update(['confirmed_bookings_count' => $r->cnt]);
        }
    }

    public function down()
    {
        // no-op
    }
}
