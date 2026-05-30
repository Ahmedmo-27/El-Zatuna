<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class BackfillCourseThumbnailAndCoverFallbacks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $fallbackPath = '/course_thumbnail_cover_fallback.png';

        // Webinars: fill missing thumbnail
        DB::table('webinars')
            ->where(function ($query) {
                $query->whereNull('thumbnail')
                    ->orWhere('thumbnail', '');
            })
            ->update([
                'thumbnail' => $fallbackPath,
            ]);

        // Webinars: fill missing cover image
        DB::table('webinars')
            ->where(function ($query) {
                $query->whereNull('image_cover')
                    ->orWhere('image_cover', '');
            })
            ->update([
                'image_cover' => $fallbackPath,
            ]);

        // Upcoming courses: fill missing thumbnail
        DB::table('upcoming_courses')
            ->where(function ($query) {
                $query->whereNull('thumbnail')
                    ->orWhere('thumbnail', '');
            })
            ->update([
                'thumbnail' => $fallbackPath,
            ]);

        // Upcoming courses: fill missing cover image
        DB::table('upcoming_courses')
            ->where(function ($query) {
                $query->whereNull('image_cover')
                    ->orWhere('image_cover', '');
            })
            ->update([
                'image_cover' => $fallbackPath,
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Data backfill migration: keeping down() empty to avoid deleting real data.
    }
}
