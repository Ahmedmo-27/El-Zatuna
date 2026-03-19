<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin users bypass Gates via Gate::before (is_admin),
        // so we only need explicit permissions for non-admin roles.
        $this->seedPanelPermissionsForUserRole();
        $this->seedPanelPermissionsForTeacherRole();
        $this->seedPanelPermissionsForOrganizationRole();
    }

    /**
     * Panel permissions for role "user" (student) – ID 1.
     * Only sections needed for students: my purchases, learning, certificates, support, forums, rewards, settings, etc.
     */
    private function seedPanelPermissionsForUserRole(): void
    {
        $roleId = 1;
        $sectionNames = [
            'panel_webinars', 'panel_webinars_lists', 'panel_webinars_my_purchases', 'panel_webinars_invited_lists',
            'panel_webinars_learning_page', 'panel_webinars_comments', 'panel_webinars_favorites', 'panel_webinars_personal_course_notes',
            'panel_upcoming_courses', 'panel_upcoming_courses_followings',
            'panel_meetings', 'panel_meetings_my_reservation',
            'panel_assignments', 'panel_assignments_lists',
            'panel_quizzes', 'panel_quizzes_my_results', 'panel_quizzes_not_participated',
            'panel_certificates', 'panel_certificates_achievements',
            'panel_products', 'panel_products_purchases', 'panel_products_my_comments',
            'panel_financial', 'panel_financial_summary', 'panel_financial_charge_account', 'panel_financial_subscribes', 'panel_financial_installments',
            'panel_support', 'panel_support_lists', 'panel_support_create', 'panel_support_tickets',
            'panel_marketing', 'panel_marketing_affiliates', 'panel_marketing_registration_bonus', 'panel_marketing_coupons', 'panel_marketing_new_coupon',
            'panel_forums', 'panel_forums_new_topic', 'panel_forums_my_topics', 'panel_forums_my_posts', 'panel_forums_bookmarks',
            'panel_rewards', 'panel_rewards_lists',
            'panel_ai_contents', 'panel_ai_contents_lists',
            'panel_notifications', 'panel_notifications_lists',
            'panel_others', 'panel_others_profile_setting', 'panel_others_logout',
        ];
        $this->syncPanelPermissionsForRole($roleId, $sectionNames);
    }

    /**
     * Panel permissions for role "teacher" – ID 4.
     * Instructor capabilities: courses, bundles, meetings, quizzes, certificates, financial, support, marketing, blog, noticeboard, etc.
     */
    private function seedPanelPermissionsForTeacherRole(): void
    {
        $roleId = 4;
        $sectionNames = [
            'panel_webinars', 'panel_webinars_lists', 'panel_webinars_create', 'panel_webinars_invited_lists', 'panel_webinars_my_purchases',
            'panel_webinars_my_class_comments', 'panel_webinars_comments', 'panel_webinars_favorites', 'panel_webinars_personal_course_notes',
            'panel_webinars_learning_page', 'panel_webinars_delete', 'panel_webinars_duplicate', 'panel_webinars_export_students_list', 'panel_webinars_invoice', 'panel_webinars_statistics',
            'panel_upcoming_courses', 'panel_upcoming_courses_lists', 'panel_upcoming_courses_create', 'panel_upcoming_courses_followings', 'panel_upcoming_courses_delete', 'panel_upcoming_courses_followers',
            'panel_bundles', 'panel_bundles_lists', 'panel_bundles_create', 'panel_bundles_delete', 'panel_bundles_export_students_list', 'panel_bundles_courses',
            'panel_meetings', 'panel_meetings_my_reservation', 'panel_meetings_requests', 'panel_meetings_settings',
            'panel_assignments', 'panel_assignments_lists', 'panel_assignments_my_courses_assignments', 'panel_assignments_students',
            'panel_quizzes', 'panel_quizzes_lists', 'panel_quizzes_create', 'panel_quizzes_results', 'panel_quizzes_my_results', 'panel_quizzes_not_participated', 'panel_quizzes_delete',
            'panel_certificates', 'panel_certificates_lists', 'panel_certificates_achievements', 'panel_certificates_course_certificates',
            'panel_products', 'panel_products_lists', 'panel_products_create', 'panel_products_sales', 'panel_products_purchases', 'panel_products_comments', 'panel_products_my_comments', 'panel_products_delete',
            'panel_financial', 'panel_financial_sales_reports', 'panel_financial_summary', 'panel_financial_payout', 'panel_financial_charge_account',
            'panel_financial_subscribes', 'panel_financial_registration_packages', 'panel_financial_installments',
            'panel_support', 'panel_support_lists', 'panel_support_create', 'panel_support_tickets',
            'panel_marketing', 'panel_marketing_special_offers', 'panel_marketing_promotions', 'panel_marketing_affiliates', 'panel_marketing_registration_bonus',
            'panel_marketing_coupons', 'panel_marketing_new_coupon', 'panel_marketing_delete_coupon',
            'panel_forums', 'panel_forums_new_topic', 'panel_forums_my_topics', 'panel_forums_my_posts', 'panel_forums_bookmarks',
            'panel_blog', 'panel_blog_new_article', 'panel_blog_my_articles', 'panel_blog_comments', 'panel_blog_delete_article',
            'panel_noticeboard', 'panel_noticeboard_history', 'panel_noticeboard_create', 'panel_noticeboard_delete',
            'panel_noticeboard_course_notices', 'panel_noticeboard_course_notices_create',
            'panel_rewards', 'panel_rewards_lists',
            'panel_ai_contents', 'panel_ai_contents_lists',
            'panel_notifications', 'panel_notifications_lists',
            'panel_others', 'panel_others_profile_setting', 'panel_others_profile_url', 'panel_others_logout',
        ];
        $this->syncPanelPermissionsForRole($roleId, $sectionNames);
    }

    /**
     * Panel permissions for role "organization" – ID 3.
     * Same as teacher plus: organization instructors/students and organization classes.
     */
    private function seedPanelPermissionsForOrganizationRole(): void
    {
        $roleId = 3;
        $sectionNames = [
            'panel_organization_instructors', 'panel_organization_instructors_lists', 'panel_organization_instructors_create', 'panel_organization_instructors_edit', 'panel_organization_instructors_delete',
            'panel_organization_students', 'panel_organization_students_lists', 'panel_organization_students_create', 'panel_organization_students_edit', 'panel_organization_students_delete',
            'panel_webinars', 'panel_webinars_lists', 'panel_webinars_create', 'panel_webinars_invited_lists', 'panel_webinars_my_purchases', 'panel_webinars_organization_classes',
            'panel_webinars_my_class_comments', 'panel_webinars_comments', 'panel_webinars_favorites', 'panel_webinars_personal_course_notes',
            'panel_webinars_learning_page', 'panel_webinars_delete', 'panel_webinars_duplicate', 'panel_webinars_export_students_list', 'panel_webinars_invoice', 'panel_webinars_statistics',
            'panel_upcoming_courses', 'panel_upcoming_courses_lists', 'panel_upcoming_courses_create', 'panel_upcoming_courses_followings', 'panel_upcoming_courses_delete', 'panel_upcoming_courses_followers',
            'panel_bundles', 'panel_bundles_lists', 'panel_bundles_create', 'panel_bundles_delete', 'panel_bundles_export_students_list', 'panel_bundles_courses',
            'panel_meetings', 'panel_meetings_my_reservation', 'panel_meetings_requests', 'panel_meetings_settings',
            'panel_assignments', 'panel_assignments_lists', 'panel_assignments_my_courses_assignments', 'panel_assignments_students',
            'panel_quizzes', 'panel_quizzes_lists', 'panel_quizzes_create', 'panel_quizzes_results', 'panel_quizzes_my_results', 'panel_quizzes_not_participated', 'panel_quizzes_delete',
            'panel_certificates', 'panel_certificates_lists', 'panel_certificates_achievements', 'panel_certificates_course_certificates',
            'panel_products', 'panel_products_lists', 'panel_products_create', 'panel_products_sales', 'panel_products_purchases', 'panel_products_comments', 'panel_products_my_comments', 'panel_products_delete',
            'panel_financial', 'panel_financial_sales_reports', 'panel_financial_summary', 'panel_financial_payout', 'panel_financial_charge_account',
            'panel_financial_subscribes', 'panel_financial_registration_packages', 'panel_financial_installments',
            'panel_support', 'panel_support_lists', 'panel_support_create', 'panel_support_tickets',
            'panel_marketing', 'panel_marketing_special_offers', 'panel_marketing_promotions', 'panel_marketing_affiliates', 'panel_marketing_registration_bonus',
            'panel_marketing_coupons', 'panel_marketing_new_coupon', 'panel_marketing_delete_coupon',
            'panel_forums', 'panel_forums_new_topic', 'panel_forums_my_topics', 'panel_forums_my_posts', 'panel_forums_bookmarks',
            'panel_blog', 'panel_blog_new_article', 'panel_blog_my_articles', 'panel_blog_comments', 'panel_blog_delete_article',
            'panel_noticeboard', 'panel_noticeboard_history', 'panel_noticeboard_create', 'panel_noticeboard_delete',
            'panel_noticeboard_course_notices', 'panel_noticeboard_course_notices_create',
            'panel_rewards', 'panel_rewards_lists',
            'panel_ai_contents', 'panel_ai_contents_lists',
            'panel_notifications', 'panel_notifications_lists',
            'panel_others', 'panel_others_profile_setting', 'panel_others_profile_url', 'panel_others_logout',
        ];
        $this->syncPanelPermissionsForRole($roleId, $sectionNames);
    }

    private function syncPanelPermissionsForRole(int $roleId, array $sectionNames): void
    {
        foreach ($sectionNames as $name) {
            $section = \App\Models\Section::where('name', $name)->first();
            if ($section) {
                \App\Models\Permission::updateOrCreate(
                    ['role_id' => $roleId, 'section_id' => $section->id],
                    ['allow' => true]
                );
            }
        }
    }
}
