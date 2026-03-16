<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Translation\FileTranslation;
use App\Models\Translation\WebinarChapterTranslation;
use App\Models\Webinar;
use App\Models\WebinarChapter;
use App\Models\WebinarChapterItem;
use Illuminate\Database\Seeder;

class FreeCoursesContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = time();

        $courses = Webinar::query()
            ->whereIn('slug', [
                'free-course-laravel-basics',
                'free-course-intro-to-web-design',
            ])
            ->get();

        if ($courses->isEmpty()) {
            $this->command->info('No free courses found. Skipping free course content seeding.');
            return;
        }

        foreach ($courses as $course) {
            $teacherId = $course->teacher_id ?: $course->creator_id;

            if (empty($teacherId)) {
                continue;
            }

            $chapter = WebinarChapter::updateOrCreate([
                'user_id' => $teacherId,
                'webinar_id' => $course->id,
                'order' => 1,
            ], [
                'status' => WebinarChapter::$chapterActive,
                'created_at' => $now,
            ]);

            WebinarChapterTranslation::updateOrCreate([
                'webinar_chapter_id' => $chapter->id,
                'locale' => 'en',
            ], [
                'title' => 'Course Introduction',
            ]);

            $videos = [
                [
                    'order' => 1,
                    'url' => 'https://www.w3schools.com/html/mov_bbb.mp4',
                    'title' => 'Welcome Video',
                    'description' => 'Introduction and orientation for this course.',
                ],
                [
                    'order' => 2,
                    'url' => 'https://www.w3schools.com/html/movie.mp4',
                    'title' => 'Core Concepts Video',
                    'description' => 'Main lesson content for the first module.',
                ],
            ];

            foreach ($videos as $video) {
                $file = File::updateOrCreate([
                    'webinar_id' => $course->id,
                    'chapter_id' => $chapter->id,
                    'order' => $video['order'],
                    'file' => $video['url'],
                ], [
                    'creator_id' => $teacherId,
                    'accessibility' => 'free',
                    'price' => 0,
                    'downloadable' => true,
                    'storage' => 'external_link',
                    'volume' => '120',
                    'file_type' => 'video',
                    'secure_host_upload_type' => null,
                    'interactive_type' => null,
                    'interactive_file_name' => null,
                    'interactive_file_path' => null,
                    'status' => File::$Active,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                FileTranslation::updateOrCreate([
                    'file_id' => $file->id,
                    'locale' => 'en',
                ], [
                    'title' => $video['title'],
                    'description' => $video['description'],
                ]);

                WebinarChapterItem::makeItem($teacherId, $chapter->id, $file->id, WebinarChapterItem::$chapterFile);
            }
        }
    }
}
