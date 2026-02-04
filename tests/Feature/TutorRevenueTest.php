<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Role;
use App\Models\Sale;
use App\Models\Setting;
use App\Models\Translation\CategoryTranslation;
use App\Models\Webinar;
use App\Services\TutorRevenueService;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TutorRevenueTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_records_course_revenue_and_updates_tutor_balance()
    {
        $role = Role::create([
            'name' => 'teacher',
            'caption' => 'Teacher',
            'users_count' => 0,
            'is_admin' => false,
            'created_at' => time(),
        ]);

        $teacher = User::create([
            'full_name' => 'Tutor Test',
            'role_name' => 'teacher',
            'role_id' => $role->id,
            'email' => 'tutor@test.com',
            'password' => bcrypt('123456'),
            'status' => 'active',
            'created_at' => time(),
        ]);

        $student = User::create([
            'full_name' => 'Student Test',
            'role_name' => 'user',
            'role_id' => $role->id,
            'email' => 'student@test.com',
            'password' => bcrypt('123456'),
            'status' => 'active',
            'created_at' => time(),
        ]);

        $category = Category::create([
            'parent_id' => null,
            'slug' => Category::makeSlug('Test Category'),
            'order' => 1,
        ]);

        CategoryTranslation::create([
            'category_id' => $category->id,
            'locale' => 'en',
            'title' => 'Test Category',
        ]);

        $webinar = Webinar::create([
            'type' => Webinar::$course,
            'teacher_id' => $teacher->id,
            'creator_id' => $teacher->id,
            'slug' => Webinar::makeSlug('Test Course'),
            'thumbnail' => '/assets/default/img/activity/48.svg',
            'image_cover' => '/assets/default/img/activity/125.svg',
            'duration' => 120,
            'support' => true,
            'certificate' => true,
            'downloadable' => true,
            'partner_instructor' => false,
            'subscribe' => false,
            'private' => false,
            'forum' => true,
            'access_days' => 30,
            'price' => 100,
            'category_id' => $category->id,
            'status' => Webinar::$active,
            'created_at' => time(),
        ]);

        Setting::updateOrCreate(['name' => 'financial'], [
            'page' => 'financial',
            'updated_at' => time(),
        ]);

        $order = Order::create([
            'user_id' => $student->id,
            'status' => Order::$paid,
            'payment_method' => Order::$credit,
            'amount' => 100,
            'tax' => 0,
            'total_discount' => 0,
            'total_amount' => 100,
            'created_at' => time(),
        ]);

        $orderItem = OrderItem::create([
            'user_id' => $student->id,
            'order_id' => $order->id,
            'webinar_id' => $webinar->id,
            'amount' => 100,
            'tax_price' => 0,
            'commission_price' => 0,
            'discount' => 0,
            'total_amount' => 100,
            'created_at' => time(),
        ]);

        $sale = Sale::create([
            'buyer_id' => $student->id,
            'seller_id' => $teacher->id,
            'order_id' => $order->id,
            'webinar_id' => $webinar->id,
            'type' => Order::$webinar,
            'payment_method' => Sale::$credit,
            'amount' => 100,
            'tax' => 0,
            'commission' => 0,
            'discount' => 0,
            'total_amount' => 100,
            'created_at' => time(),
        ]);

        (new TutorRevenueService())->recordCourseSale($orderItem, $sale);

        $this->assertDatabaseHas('course_revenues', [
            'course_id' => $webinar->id,
            'student_id' => $student->id,
        ]);

        $this->assertDatabaseHas('tutors', [
            'user_id' => $teacher->id,
        ]);
    }
}
