<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\User;
use App\Models\Webinar;

class FreeCoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teacher = User::where('role_name', 'teacher')->first();

        if (!$teacher) {
            $this->command->info('No teacher found. Skipping free course seeding.');
            return;
        }

        $freeCourses = [
            [
                'teacher_id' => $teacher->id,
                'creator_id' => $teacher->id,
                'slug' => 'free-course-laravel-basics',
                'type' => 'course',
                'private' => false,
                'status' => 'active',
                'start_date' => time(),
                'duration' => 60,
                'price' => 0,
                'created_at' => time(),
                'updated_at' => time(),
                
                // Translatable fields
                'title' => 'Laravel Basics for Beginners',
                'seo_description' => 'Learn Laravel from scratch.',
                'description' => 'A complete guide to starting with Laravel.',
                'summary' => 'Introduction to Laravel framwork.',
            ],
            [
                'teacher_id' => $teacher->id,
                'creator_id' => $teacher->id,
                'slug' => 'free-course-intro-to-web-design',
                'type' => 'course',
                'private' => false,
                'status' => 'active',
                'start_date' => time(),
                'duration' => 120,
                'price' => 0,
                'created_at' => time(),
                'updated_at' => time(),

                // Translatable fields
                'title' => 'Introduction to Web Design',
                'seo_description' => 'Web design fundamentals.',
                'description' => 'Learn the basics of designing beautiful websites.',
                'summary' => 'Design principles and HTML/CSS basics.',
            ]
        ];

        foreach ($freeCourses as $courseData) {
            // Check if exists using the model's slug check or just try/catch
            if (Webinar::where('slug', $courseData['slug'])->exists()) {
                continue;
            }

            Webinar::create($courseData);
        }
    }
}
