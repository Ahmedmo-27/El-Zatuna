<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\User;
use App\Models\SpecialOffer;
use App\Models\Webinar;

class DiscountedCoursesSeeder extends Seeder
{
    public function run()
    {
        $teacher = User::where('role_name', 'teacher')->first();

        if (!$teacher) {
            $this->command->info('No teacher found in database.');
            return;
        }

        $now = time();

        $discountedCourses = [
            [
                'slug' => 'discounted-course-laravel-fundamentals',
                'title' => 'Laravel Fundamentals',
                'seo_description' => 'Build robust Laravel apps with this discounted course.',
                'description' => 'Learn Laravel routing, models, and controllers through practical examples.',
                'summary' => 'Hands-on Laravel essentials with a limited-time discount.',
                'price' => 120,
                'duration' => 180,
                'discount_percent' => 25,
            ],
            [
                'slug' => 'discounted-course-data-analytics-intro',
                'title' => 'Intro to Data Analytics',
                'seo_description' => 'Start your analytics journey with discounted access.',
                'description' => 'Master data cleaning, reporting, and dashboard fundamentals.',
                'summary' => 'Beginner-friendly analytics course with active discount.',
                'price' => 140,
                'duration' => 210,
                'discount_percent' => 30,
            ],
            [
                'slug' => 'discounted-course-design-systems-101',
                'title' => 'Design Systems 101',
                'seo_description' => 'Learn scalable UI systems with this special discounted course.',
                'description' => 'Create consistent design libraries and component standards.',
                'summary' => 'Practical design systems training with reduced price.',
                'price' => 110,
                'duration' => 160,
                'discount_percent' => 20,
            ],
        ];

        foreach ($discountedCourses as $courseData) {
            $webinar = Webinar::updateOrCreate([
                'slug' => $courseData['slug'],
            ], [
                'teacher_id' => $teacher->id,
                'creator_id' => $teacher->id,
                'type' => Webinar::$course,
                'private' => false,
                'status' => Webinar::$active,
                'start_date' => $now,
                'duration' => $courseData['duration'],
                'price' => $courseData['price'],
                'created_at' => $now,
                'updated_at' => $now,
                'title' => $courseData['title'],
                'seo_description' => $courseData['seo_description'],
                'description' => $courseData['description'],
                'summary' => $courseData['summary'],
            ]);

            SpecialOffer::updateOrCreate([
                'webinar_id' => $webinar->id,
            ], [
                'creator_id' => $teacher->id,
                'name' => $courseData['title'] . ' Discount',
                'percent' => $courseData['discount_percent'],
                'status' => SpecialOffer::$active,
                'from_date' => $now - 3600,
                'to_date' => $now + (86400 * 30),
                'created_at' => $now,
            ]);
        }

        $this->command->info('Seeded discounted courses with active special offers.');
    }
}
