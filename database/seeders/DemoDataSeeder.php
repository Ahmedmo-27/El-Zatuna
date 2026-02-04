<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Accounting;
use App\Models\Faculty;
use App\Models\University;
use App\Models\Translation\CategoryTranslation;
use App\Models\Translation\FileTranslation;
use App\Models\Translation\SessionTranslation;
use App\Models\Translation\TextLessonTranslation;
use App\Models\Translation\WebinarChapterTranslation;
use App\Models\Translation\WebinarTranslation;
use App\Models\Tutor;
use App\Models\Webinar;
use App\Models\WebinarChapter;
use App\Models\WebinarChapterItem;
use App\Models\File;
use App\Models\Session;
use App\Models\TextLesson;
use App\Models\Role;
use App\Models\SpecialOffer;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\SectionsTableSeeder;
use Database\Seeders\PaymentChannelsTableSeeder;
use Database\Seeders\LandingBuilderComponentsSeeder;
use Database\Seeders\ThemeHeaderFooterSeeder;
use Database\Seeders\DefaultThemeSeeder;
use Database\Seeders\HomeLandingSeeder;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RolesTableSeeder::class,
            UsersTableSeeder::class,
            SectionsTableSeeder::class,
            PaymentChannelsTableSeeder::class,
            LandingBuilderComponentsSeeder::class,
            ThemeHeaderFooterSeeder::class,
            DefaultThemeSeeder::class,
            HomeLandingSeeder::class,
        ]);

        $now = time();

        $universities = [
            'Northlake University',
            'Horizon State University',
            'Crestview Institute',
            'Riverside College',
            'Summit Tech University',
        ];

        $universityModels = [];
        foreach ($universities as $name) {
            $universityModels[$name] = University::firstOrCreate(['name' => $name]);
        }

        $faculties = [
            ['name' => 'Engineering', 'university' => 'Northlake University'],
            ['name' => 'Business', 'university' => 'Northlake University'],
            ['name' => 'Medicine', 'university' => 'Northlake University'],
            ['name' => 'Computer Science', 'university' => 'Horizon State University'],
            ['name' => 'Arts & Humanities', 'university' => 'Horizon State University'],
            ['name' => 'Mathematics', 'university' => 'Horizon State University'],
            ['name' => 'Education', 'university' => 'Crestview Institute'],
            ['name' => 'Law', 'university' => 'Crestview Institute'],
            ['name' => 'Finance', 'university' => 'Riverside College'],
            ['name' => 'Architecture', 'university' => 'Riverside College'],
            ['name' => 'Data & AI', 'university' => 'Summit Tech University'],
            ['name' => 'Cloud Computing', 'university' => 'Summit Tech University'],
        ];

        $facultyModels = [];
        foreach ($faculties as $faculty) {
            $university = $universityModels[$faculty['university']];
            $facultyModels[$faculty['name']] = Faculty::firstOrCreate([
                'name' => $faculty['name'],
                'university_id' => $university->id,
            ]);
        }

        $categories = [
            'Web Development',
            'Data Science',
            'Design',
            'Cybersecurity',
            'Mobile Development',
            'Cloud Computing',
            'Business',
            'Marketing',
            'UI/UX',
        ];

        $categoryModels = [];
        foreach ($categories as $title) {
            $translation = CategoryTranslation::where('locale', 'en')
                ->where('title', $title)
                ->first();

            if ($translation && $translation->category) {
                $categoryModels[$title] = $translation->category;
                continue;
            }

            $category = Category::create([
                'parent_id' => null,
                'slug' => Category::makeSlug($title),
                'icon' => null,
                'cover_image' => null,
                'icon2' => null,
                'icon2_box_color' => null,
                'overlay_image' => null,
                'order' => Category::whereNull('parent_id')->count() + 1,
            ]);

            CategoryTranslation::updateOrCreate([
                'category_id' => $category->id,
                'locale' => 'en',
            ], [
                'title' => $title,
            ]);

            $categoryModels[$title] = $category;
        }

        $teacherRole = Role::where('name', Role::$teacher)->first();
        $studentRole = Role::where('name', Role::$user)->first();

        $teacher1 = User::updateOrCreate([
            'email' => 'teacher1@example.com'
        ], [
            'full_name' => 'Ava Teacher',
            'mobile' => '09000000001',
            'role_name' => Role::$teacher,
            'role_id' => $teacherRole ? $teacherRole->id : 4,
            'password' => password_hash('123456', PASSWORD_BCRYPT),
            'status' => 'active',
            'university_id' => $universityModels['Northlake University']->id,
            'faculty_id' => $facultyModels['Engineering']->id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $teacher2 = User::updateOrCreate([
            'email' => 'teacher2@example.com'
        ], [
            'full_name' => 'Noah Teacher',
            'mobile' => '09000000002',
            'role_name' => Role::$teacher,
            'role_id' => $teacherRole ? $teacherRole->id : 4,
            'password' => password_hash('123456', PASSWORD_BCRYPT),
            'status' => 'active',
            'university_id' => $universityModels['Horizon State University']->id,
            'faculty_id' => $facultyModels['Computer Science']->id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $teacher3 = User::updateOrCreate([
            'email' => 'teacher3@example.com'
        ], [
            'full_name' => 'Sophia Teacher',
            'mobile' => '09000000005',
            'role_name' => Role::$teacher,
            'role_id' => $teacherRole ? $teacherRole->id : 4,
            'password' => password_hash('123456', PASSWORD_BCRYPT),
            'status' => 'active',
            'university_id' => $universityModels['Crestview Institute']->id,
            'faculty_id' => $facultyModels['Education']->id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $teacher4 = User::updateOrCreate([
            'email' => 'teacher4@example.com'
        ], [
            'full_name' => 'Ethan Teacher',
            'mobile' => '09000000006',
            'role_name' => Role::$teacher,
            'role_id' => $teacherRole ? $teacherRole->id : 4,
            'password' => password_hash('123456', PASSWORD_BCRYPT),
            'status' => 'active',
            'university_id' => $universityModels['Summit Tech University']->id,
            'faculty_id' => $facultyModels['Data & AI']->id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $student1 = User::updateOrCreate([
            'email' => 'student1@example.com'
        ], [
            'full_name' => 'Mia Student',
            'mobile' => '09000000003',
            'role_name' => Role::$user,
            'role_id' => $studentRole ? $studentRole->id : 1,
            'password' => password_hash('123456', PASSWORD_BCRYPT),
            'status' => 'active',
            'university_id' => $universityModels['Northlake University']->id,
            'faculty_id' => $facultyModels['Engineering']->id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $student2 = User::updateOrCreate([
            'email' => 'student2@example.com'
        ], [
            'full_name' => 'Liam Student',
            'mobile' => '09000000004',
            'role_name' => Role::$user,
            'role_id' => $studentRole ? $studentRole->id : 1,
            'password' => password_hash('123456', PASSWORD_BCRYPT),
            'status' => 'active',
            'university_id' => $universityModels['Horizon State University']->id,
            'faculty_id' => $facultyModels['Computer Science']->id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $student3 = User::updateOrCreate([
            'email' => 'student3@example.com'
        ], [
            'full_name' => 'Olivia Student',
            'mobile' => '09000000007',
            'role_name' => Role::$user,
            'role_id' => $studentRole ? $studentRole->id : 1,
            'password' => password_hash('123456', PASSWORD_BCRYPT),
            'status' => 'active',
            'university_id' => $universityModels['Crestview Institute']->id,
            'faculty_id' => $facultyModels['Law']->id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $student4 = User::updateOrCreate([
            'email' => 'student4@example.com'
        ], [
            'full_name' => 'James Student',
            'mobile' => '09000000008',
            'role_name' => Role::$user,
            'role_id' => $studentRole ? $studentRole->id : 1,
            'password' => password_hash('123456', PASSWORD_BCRYPT),
            'status' => 'active',
            'university_id' => $universityModels['Riverside College']->id,
            'faculty_id' => $facultyModels['Finance']->id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $student5 = User::updateOrCreate([
            'email' => 'student5@example.com'
        ], [
            'full_name' => 'Amelia Student',
            'mobile' => '09000000009',
            'role_name' => Role::$user,
            'role_id' => $studentRole ? $studentRole->id : 1,
            'password' => password_hash('123456', PASSWORD_BCRYPT),
            'status' => 'active',
            'university_id' => $universityModels['Summit Tech University']->id,
            'faculty_id' => $facultyModels['Cloud Computing']->id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $seedWalletUsers = [$teacher1, $teacher2, $teacher3, $teacher4, $student1, $student2, $student3, $student4, $student5];
        foreach ($seedWalletUsers as $walletUser) {
            Accounting::create([
                'user_id' => $walletUser->id,
                'amount' => rand(500, 600),
                'type' => Accounting::$addiction,
                'type_account' => Accounting::$asset,
                'description' => 'Seed wallet balance',
                'system' => false,
                'tax' => false,
                'created_at' => $now,
            ]);
        }

        $teachers = [$teacher1, $teacher2, $teacher3, $teacher4];
        foreach ($teachers as $teacher) {
            Tutor::firstOrCreate([
                'user_id' => $teacher->id,
            ], [
                'payout_balance' => 0,
                'created_at' => $now,
            ]);
        }

        $thumbnail = '/assets/default/img/activity/48.svg';
        $cover = '/assets/default/img/activity/125.svg';

        $courseSpecs = [
            [
                'title' => 'Laravel Fundamentals',
                'summary' => 'Learn Laravel basics with hands-on projects.',
                'description' => 'A full introduction to routes, controllers, views, and Eloquent.',
                'teacher' => $teacher1,
                'category' => $categoryModels['Web Development'],
                'university' => $universityModels['Northlake University']->id,
                'faculty' => $facultyModels['Engineering']->id,
                'type' => Webinar::$course,
            ],
            [
                'title' => 'Intro to Data Analytics',
                'summary' => 'Clean, analyze, and visualize data using practical methods.',
                'description' => 'Covers data cleaning, exploratory analysis, and dashboards.',
                'teacher' => $teacher2,
                'category' => $categoryModels['Data Science'],
                'university' => $universityModels['Horizon State University']->id,
                'faculty' => $facultyModels['Computer Science']->id,
                'type' => Webinar::$course,
            ],
            [
                'title' => 'Design Systems 101',
                'summary' => 'Build consistent UI design systems for teams.',
                'description' => 'Tokens, components, and scalable design workflows.',
                'teacher' => $teacher1,
                'category' => $categoryModels['Design'],
                'university' => null,
                'faculty' => null,
                'type' => Webinar::$course,
            ],
            [
                'title' => 'Live Workshop: Modern PHP',
                'summary' => 'Live class on PHP 8+ features and best practices.',
                'description' => 'Explore modern syntax, typing, and performance tips.',
                'teacher' => $teacher2,
                'category' => $categoryModels['Web Development'],
                'university' => null,
                'faculty' => null,
                'type' => Webinar::$webinar,
            ],
            [
                'title' => 'Cloud Infrastructure Essentials',
                'summary' => 'Learn cloud services, scaling, and cost management.',
                'description' => 'Compute, storage, networking, and monitoring basics.',
                'teacher' => $teacher4,
                'category' => $categoryModels['Cloud Computing'],
                'university' => $universityModels['Summit Tech University']->id,
                'faculty' => $facultyModels['Cloud Computing']->id,
                'type' => Webinar::$course,
            ],
        ];

        $createdCourses = [];

        foreach ($courseSpecs as $spec) {
            $slug = Str::slug($spec['title']) . '-' . Str::random(6);

            $webinar = Webinar::firstOrCreate([
                'slug' => $slug,
            ], [
                'type' => $spec['type'],
                'teacher_id' => $spec['teacher']->id,
                'creator_id' => $spec['teacher']->id,
                'thumbnail' => $thumbnail,
                'image_cover' => $cover,
                'video_demo' => null,
                'video_demo_source' => null,
                'capacity' => $spec['type'] == Webinar::$webinar ? 50 : null,
                'start_date' => $spec['type'] == Webinar::$webinar ? ($now + 86400 * 3) : null,
                'timezone' => null,
                'duration' => 120,
                'support' => true,
                'certificate' => true,
                'downloadable' => true,
                'partner_instructor' => false,
                'subscribe' => false,
                'private' => false,
                'forum' => true,
                'access_days' => 90,
                'price' => 49,
                'organization_price' => null,
                'points' => 10,
                'category_id' => $spec['category']->id,
                'message_for_reviewer' => null,
                'status' => Webinar::$active,
                'university_id' => $spec['university'],
                'faculty_id' => $spec['faculty'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            WebinarTranslation::updateOrCreate([
                'webinar_id' => $webinar->id,
                'locale' => 'en',
            ], [
                'title' => $spec['title'],
                'summary' => $spec['summary'],
                'description' => $spec['description'],
                'seo_description' => $spec['summary'],
            ]);

            if ($spec['type'] === Webinar::$course) {
                $createdCourses[] = $webinar;
            }

            $teacherId = $spec['teacher']->id;

            $chapterConfigs = [
                ['key' => 'file', 'order' => 1, 'title' => 'Module 1: Getting Started'],
                ['key' => 'session', 'order' => 2, 'title' => 'Live Q&A Session'],
                ['key' => 'text', 'order' => 3, 'title' => 'Reading Materials'],
            ];

            $chapters = [];
            foreach ($chapterConfigs as $config) {
                $chapter = WebinarChapter::updateOrCreate([
                    'user_id' => $teacherId,
                    'webinar_id' => $webinar->id,
                    'order' => $config['order'],
                ], [
                    'status' => WebinarChapter::$chapterActive,
                    'created_at' => $now,
                ]);

                WebinarChapterTranslation::updateOrCreate([
                    'webinar_chapter_id' => $chapter->id,
                    'locale' => 'en',
                ], [
                    'title' => $config['title'],
                ]);

                $chapters[$config['key']] = $chapter;
            }

            $introFile = File::updateOrCreate([
                'webinar_id' => $webinar->id,
                'chapter_id' => $chapters['file']->id,
                'order' => 1,
                'file' => 'https://www.w3schools.com/html/mov_bbb.mp4',
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
            ]);

            FileTranslation::updateOrCreate([
                'file_id' => $introFile->id,
                'locale' => 'en',
            ], [
                'title' => 'Intro Lecture',
                'description' => 'Kickoff video for the course content.',
            ]);

            WebinarChapterItem::makeItem($teacherId, $introFile->chapter_id, $introFile->id, WebinarChapterItem::$chapterFile);

            $advancedFile = File::updateOrCreate([
                'webinar_id' => $webinar->id,
                'chapter_id' => $chapters['file']->id,
                'order' => 2,
                'file' => 'https://www.w3schools.com/html/movie.mp4',
            ], [
                'creator_id' => $teacherId,
                'accessibility' => 'paid',
                'price' => 15,
                'downloadable' => true,
                'storage' => 'external_link',
                'volume' => '180',
                'file_type' => 'video',
                'secure_host_upload_type' => null,
                'interactive_type' => null,
                'interactive_file_name' => null,
                'interactive_file_path' => null,
                'status' => File::$Active,
                'created_at' => $now,
            ]);

            FileTranslation::updateOrCreate([
                'file_id' => $advancedFile->id,
                'locale' => 'en',
            ], [
                'title' => 'Advanced Lecture',
                'description' => 'Deeper dive into the topic with practical examples.',
            ]);

            WebinarChapterItem::makeItem($teacherId, $advancedFile->chapter_id, $advancedFile->id, WebinarChapterItem::$chapterFile);

            $session = Session::updateOrCreate([
                'webinar_id' => $webinar->id,
                'chapter_id' => $chapters['session']->id,
                'order' => 1,
            ], [
                'creator_id' => $teacherId,
                'date' => $now + 86400 * 7,
                'duration' => 60,
                'link' => 'https://example.com/session',
                'session_api' => 'local',
                'api_secret' => null,
                'status' => Session::$Active,
                'created_at' => $now,
            ]);

            SessionTranslation::updateOrCreate([
                'session_id' => $session->id,
                'locale' => 'en',
            ], [
                'title' => 'Live Session',
                'description' => 'Join the instructor for a live walkthrough and Q&A.',
            ]);

            WebinarChapterItem::makeItem($teacherId, $session->chapter_id, $session->id, WebinarChapterItem::$chapterSession);

            $textLesson = TextLesson::updateOrCreate([
                'webinar_id' => $webinar->id,
                'chapter_id' => $chapters['text']->id,
                'order' => 1,
            ], [
                'creator_id' => $teacherId,
                'image' => null,
                'study_time' => 20,
                'accessibility' => 'free',
                'status' => TextLesson::$Active,
                'created_at' => $now,
            ]);

            TextLessonTranslation::updateOrCreate([
                'text_lesson_id' => $textLesson->id,
                'locale' => 'en',
            ], [
                'title' => 'Lesson Notes',
                'summary' => 'Key takeaways and references for this module.',
                'content' => '<p>Use these notes to review the lecture and practice the exercises.</p>',
            ]);

            WebinarChapterItem::makeItem($teacherId, $textLesson->chapter_id, $textLesson->id, WebinarChapterItem::$chapterTextLesson);
        }

        $this->command->info('Demo data seeded: universities, faculties, users, and courses.');
    }
}

        $discountCourses = array_slice($createdCourses, 0, 3);
        foreach ($discountCourses as $index => $discountCourse) {
            SpecialOffer::firstOrCreate([
                'webinar_id' => $discountCourse->id,
            ], [
                'creator_id' => $discountCourse->creator_id,
                'name' => 'Homepage Discount ' . ($index + 1),
                'percent' => 20 + ($index * 5),
                'status' => SpecialOffer::$active,
                'created_at' => $now,
                'from_date' => $now - 3600,
                'to_date' => $now + (86400 * 30),
            ]);
        }
