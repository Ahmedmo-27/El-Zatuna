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
        CourseForumAnswer::class => CourseForumAnswerPolicy::class ,
        Webinar::class => WebinarPolicy::class
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
            if (app()->environment('local')) {
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
