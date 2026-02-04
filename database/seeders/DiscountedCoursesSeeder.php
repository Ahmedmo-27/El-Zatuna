<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\User;
use App\Models\Webinar;
use App\Models\Ticket;
use App\Models\Discount;

class DiscountedCoursesSeeder extends Seeder
{
    public function run()
    {
        $teacher = User::where('role_name', 'teacher')->first();

        if (!$teacher) {
            $this->command->info('No teacher found in database.');
            return;
        }

        // 1. Create a course for discount
        $courseData = [
            'teacher_id' => $teacher->id,
            'creator_id' => $teacher->id,
            'slug' => 'discounted-course-example',
            'type' => 'course',
            'private' => false,
            'status' => 'active',
            'start_date' => time(),
            'duration' => 120,
            'price' => 100, // Original Price
            'created_at' => time(),
            'updated_at' => time(),
            
            'title' => 'Advanced Laravel Mastery - Discounted',
            'seo_description' => 'Master Laravel with this discounted course.',
            'description' => 'Learn advanced concepts at a reduced price.',
            'summary' => 'Limited time discount available.',
        ];

        // Ensure course doesn't exist
        $webinar = Webinar::where('slug', $courseData['slug'])->first();
        if (!$webinar) {
            $webinar = Webinar::create($courseData);
        }

        // 2. Add a Ticket (Discount)
        // Ticket logic in ClassesController: 
        // Ticket::where('start_date', '<', $now)->where('end_date', '>', $now)->whereNotNull("webinar_id")
        
        $ticketData = [
            'creator_id' => $teacher->id,
            'webinar_id' => $webinar->id,
            'start_date' => time() - 3600, // Started 1 hour ago
            'end_date' => time() + (86400 * 7), // Valid for 7 days
            'discount' => 50, // 50% off
            'capacity' => 100,
            'order' => 1,
            'title' => 'Early Bird Discount'
        ];
        
        // Remove existing tickets for this webinar to prevent duplicates?
        // Actually fine to just create one if not exists.
        
        $ticket = Ticket::where('webinar_id', $webinar->id)->where('title', 'Early Bird Discount')->first();
        
        if (!$ticket) {
            Ticket::create($ticketData);
            $this->command->info('Created discounted course and ticket.');
        } else {
            $this->command->info('Discounted course ticket already exists.');
        }
    }
}
