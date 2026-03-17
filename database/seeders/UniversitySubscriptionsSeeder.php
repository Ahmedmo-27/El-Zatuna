<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subscribe;
use App\Models\Translation\SubscribeTranslation;

class UniversitySubscriptionsSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Full access plan: access all courses for the user's university + faculty
        $fullAccess = Subscribe::firstOrCreate(
            ['type' => 'university_full_access'],
            [
                'title' => 'University Full Access',
                'usable_count' => 0,
                'days' => 365,
                'price' => 8000,
                'icon' => 'fa fa-university',
                'is_popular' => true,
                'infinite_use' => true,
                'access_all_courses' => true,
                'scoped_to_university' => true,
                'scoped_to_faculty' => true,
                'created_at' => time(),
            ]
        );

        SubscribeTranslation::updateOrCreate(
            [
                'subscribe_id' => $fullAccess->id,
                'locale' => 'en',
            ],
            [
                'title' => 'Full Access Subscription',
                'subtitle' => 'All courses in your university & faculty',
                'description' => 'Unlimited access to all sections of all courses in your university and faculty.',
            ]
        );

        // 2) 10 sections coupon plan
        $tenSections = Subscribe::firstOrCreate(
            ['type' => 'university_10_sections'],
            [
                'title' => '10 Sections Coupon',
                'usable_count' => 10,
                'days' => 365,
                'price' => 1400,
                'icon' => 'fa fa-ticket-alt',
                'is_popular' => false,
                'infinite_use' => false,
                'access_all_courses' => false,
                'scoped_to_university' => true,
                'scoped_to_faculty' => true,
                'created_at' => time(),
            ]
        );

        SubscribeTranslation::updateOrCreate(
            [
                'subscribe_id' => $tenSections->id,
                'locale' => 'en',
            ],
            [
                'title' => '10 Sections Subscription',
                'subtitle' => 'Unlock 10 free sections',
                'description' => 'Unlock any 10 paid sections from courses in your university and faculty.',
            ]
        );
    }
}

