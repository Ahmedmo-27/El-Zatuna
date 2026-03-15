<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('webinars')->whereNotNull('id')->update(['price' => 2000]);

        DB::table('webinar_chapters')->update(['price' => 150]);

        $webinarIds = DB::table('webinar_chapters')->distinct()->pluck('webinar_id');
        foreach ($webinarIds as $webinarId) {
            $first = DB::table('webinar_chapters')
                ->where('webinar_id', $webinarId)
                ->orderByRaw('COALESCE(`order`, 999999)')
                ->orderBy('id')
                ->first();
            if ($first) {
                DB::table('webinar_chapters')->where('id', $first->id)->update(['price' => 0]);
            }
        }
    }

    public function down(): void
    {
        // Optional: revert prices if you store previous values
    }
};
