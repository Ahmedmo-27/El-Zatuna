<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Backfill missing webinar_chapters.duration (minutes) from related files durations.
        //    duration on files is in minutes (see 2026_03_08_000001_add_duration_to_files_table.php).
        DB::table('webinar_chapters')
            ->select('id')
            ->orderBy('id')
            ->chunkById(200, function ($chapters) {
                foreach ($chapters as $chapter) {
                    $row = DB::table('webinar_chapters')->where('id', $chapter->id)->first(['id', 'duration']);
                    if (!$row || !is_null($row->duration)) {
                        continue;
                    }

                    $filesMinutes = (int) DB::table('files')
                        ->where('chapter_id', $row->id)
                        ->where('status', 'active')
                        ->whereNotNull('duration')
                        ->sum('duration');

                    if ($filesMinutes > 0) {
                        DB::table('webinar_chapters')
                            ->where('id', $row->id)
                            ->update(['duration' => $filesMinutes]);
                    }
                }
            });

        // 2) Apply pricing rule per section based on its duration and keep the first active section free.
        //    Then update webinars.price = sum(chapter prices).
        DB::table('webinars')
            ->select('id')
            ->orderBy('id')
            ->chunkById(200, function ($webinars) {
                foreach ($webinars as $webinar) {
                    $chapters = DB::table('webinar_chapters')
                        ->where('webinar_id', $webinar->id)
                        ->where('status', 'active')
                        ->orderByRaw('COALESCE(`order`, 999999) ASC')
                        ->orderBy('id')
                        ->get(['id', 'duration', 'price']);

                    if ($chapters->isEmpty()) {
                        continue;
                    }

                    $totalPrice = 0.0;

                    foreach ($chapters as $index => $chapter) {
                        $price = 0.0;

                        if ($index !== 0) {
                            $minutes = $chapter->duration;

                            if (is_null($minutes)) {
                                $minutes = (int) DB::table('files')
                                    ->where('chapter_id', $chapter->id)
                                    ->where('status', 'active')
                                    ->whereNotNull('duration')
                                    ->sum('duration');

                                if ($minutes > 0) {
                                    DB::table('webinar_chapters')
                                        ->where('id', $chapter->id)
                                        ->update(['duration' => $minutes]);
                                }
                            }

                            $minutes = is_null($minutes) ? 0 : (int) $minutes;

                            // Pricing tiers (minutes):
                            // <= 60 => 150
                            // >= 90 => 300
                            // >= 150 => 450
                            if ($minutes <= 60) {
                                $price = 150.0;
                            } elseif ($minutes >= 150) {
                                $price = 450.0;
                            } elseif ($minutes >= 90) {
                                $price = 300.0;
                            } else {
                                $price = 150.0;
                            }
                        }

                        if ((float) $chapter->price !== $price) {
                            DB::table('webinar_chapters')->where('id', $chapter->id)->update(['price' => $price]);
                        }

                        $totalPrice += $price;
                    }

                    DB::table('webinars')->where('id', $webinar->id)->update(['price' => $totalPrice]);
                }
            });
    }

    public function down(): void
    {
        // No rollback: this is a data backfill + recalculation migration.
    }
};

