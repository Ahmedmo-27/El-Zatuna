<?php

namespace App\Providers;

use App\Models\Api\CourseForumAnswer;
use App\Models\Webinar;
use App\Models\CourseForum;
use App\Models\Section;
use App\Policies\CourseForumAnswerPolicy;
use App\Policies\CourseForumPolicy;
use App\Policies\WebinarPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        CourseForum::class => CourseForumPolicy::class,
        CourseForumAnswer::class => CourseForumAnswerPolicy::class,
        // Webinar::class => WebinarPolicy::class  // Disabled - using controller-level checks instead
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {

        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            // Admin users have all permissions
            if ($user && $user->isAdmin()) {
                return true;
            }

            // In local environment, allow all abilities for development
            if (app()->environment('local') && $user) {
                return true;
            }

            // Allow all authenticated users to access their own panel data (view-only operations)
            // These are operations where users view their own data, not privileged actions
            $userOwnDataAbilities = [
                'panel_webinars_my_purchases',
                'panel_webinars_lists',
                'panel_webinars_invited_lists',
                'panel_webinars_learning_page',
                'panel_webinars_comments',
                'panel_webinars_favorites',
                'panel_webinars_personal_course_notes',
                'panel_financial_sales_reports',
                'panel_financial_summary',
                'panel_financial_charge_account',
                'panel_financial_payout',
                'panel_financial_installments',
                'panel_financial_subscribes',
                'panel_financial_registration_packages',
                'panel_support_lists',
                'panel_support_tickets',
                'panel_support_create',
                'panel_others_profile_setting',
                'panel_notifications_lists',
                'panel_forums_bookmarks',
                'panel_forums_my_topics',
                'panel_forums_my_posts',
                'panel_products_purchases',
                'panel_products_my_comments',
                'panel_rewards_lists',
                'panel_assignments_lists',
                'panel_assignments_my_courses_assignments',
                'panel_assignments_students',
                'panel_meetings_settings',
                'panel_meetings_my_reservation',
                'panel_meetings_requests',
                'panel_ai_contents_lists',
            ];

            if ($user && in_array($ability, $userOwnDataAbilities)) {
                return true;
            }

            return null;
        });

        try {
            if (Schema::hasTable('sections')) {
                $minutes = 60 * 60; // 1 hour
                $sections = Cache::remember('sections', $minutes, function () {
                    return Section::all();
                });

                $scopes = [];
                foreach ($sections as $section) {
                    $scopes[$section->name] = $section->caption;
                    Gate::define($section->name, function ($user) use ($section) {
                        // Admin bypass
                        if ($user->isAdmin()) {
                            return true;
                        }
                        return $user->hasPermission($section->name);
                    });
                }
            }
        } catch (\Throwable $e) {
            // Database not ready yet (e.g. during install or migrations)
        }

        //
    }
}
