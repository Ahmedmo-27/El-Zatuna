<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Apply automatic pricing for existing courses based on number of sections (chapters).
        // This only affects webinars of type "course".

        $webinarsQuery = DB::table('webinars')
            ->select('id')
            ->where('type', 'course');

        $webinarsQuery->orderBy('id')->chunkById(200, function ($webinars) {
            foreach ($webinars as $webinar) {
                $sectionsCount = DB::table('webinar_chapters')
                    ->where('webinar_id', $webinar->id)
                    ->where('status', 'active')
                    ->count();

                if ($sectionsCount <= 0) {
                    continue;
                }

                // Mirror the same pricing rules used for upcoming courses.
                $price = null;
                if (function_exists('calculateCoursePriceBySections')) {
                    $price = calculateCoursePriceBySections($sectionsCount);
                } else {
                    // Inline fallback in case helper is not loaded in migration context.
                    if ($sectionsCount < 6) {
                        $price = 150 * $sectionsCount;
                    } elseif ($sectionsCount >= 6 && $sectionsCount <= 8) {
                        $price = 1100;
                    } elseif ($sectionsCount >= 9 && $sectionsCount <= 11) {
                        $price = 1300;
                    } elseif ($sectionsCount >= 12 && $sectionsCount <= 14) {
                        $price = 1750;
                    } elseif ($sectionsCount >= 15 && $sectionsCount <= 18) {
                        $price = 2000;
                    } else {
                        $price = 2000;
                    }
                }

                if (!is_null($price)) {
                    DB::table('webinars')
                        ->where('id', $webinar->id)
                        ->update(['price' => $price]);
                }
            }
        });
    }

    public function down(): void
    {
        // No automatic rollback; prices can be adjusted manually if needed.
    }
};

