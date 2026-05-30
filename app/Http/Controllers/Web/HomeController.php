<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mixins\LandingBuilder\FrontComponentsDataMixins;
use App\Models\Role;
use App\Models\Subscribe;
use App\Models\Webinar;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $frontComponentsDataMixins = new FrontComponentsDataMixins();
        $activeTheme = getActiveTheme();
        $homeLanding = !empty($activeTheme) ? $activeTheme->homeLanding : null;

        $instructorsQuery = User::query()
            ->where('role_name', Role::$teacher)
            ->where('status', 'active');

        $instructorsCount = (clone $instructorsQuery)->count();

        $professionalCoursesQuery = Webinar::query()
            ->where('status', Webinar::$active)
            ->where('private', false)
            ->where('type', Webinar::$course);

        // Guests should only see global courses in the stats as well.
        if (!auth()->check()) {
            $professionalCoursesQuery->whereNull('university_id')
                ->whereNull('faculty_id');
        }

        $professionalCoursesCount = $professionalCoursesQuery->count();

        $seoSettings = getSeoMetas('home');
        $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('home.home_title');
        $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('home.home_title');
        $pageRobot = getPageRobot('home');

        $discountedCourses = $frontComponentsDataMixins->getDiscountedCoursesData(6)
            ->filter(function ($course) {
                if (empty($course->price) || $course->price <= 0) {
                    return false;
                }

                return $course->bestTicket() < $course->price;
            })
            ->take(3)
            ->values();

        $upcomingCourses = $frontComponentsDataMixins->getUpcomingCoursesData(6);

        $freeCourses = $frontComponentsDataMixins->getFreeCoursesData(3);
        if ($freeCourses->isEmpty()) {
            $fallbackFreeQuery = Webinar::query()
                ->where('status', Webinar::$active)
                ->where('private', false)
                ->where(function ($query) {
                    $query->whereNull('price')->orWhere('price', 0);
                })
                ->with(['teacher', 'university', 'faculty']);

            if (!auth()->check()) {
                $fallbackFreeQuery->whereNull('university_id')
                    ->whereNull('faculty_id');
            }

            $freeCourses = $fallbackFreeQuery
                ->orderBy('updated_at', 'desc')
                ->limit(3)
                ->get();
        }

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'pageCanonicalUrl' => url('/'),
            'pageOgType' => 'website',
            'activeTheme' => $activeTheme,
            'homeLanding' => $homeLanding,
            'professionalCoursesCount' => $professionalCoursesCount,
            'instructorsCount' => $instructorsCount,
            'discountedCourses' => $discountedCourses,
            'upcomingCourses' => $upcomingCourses,
            'freeCourses' => $freeCourses,
            'subscriptionPlans' => Subscribe::query()
                ->orderByDesc('is_popular')
                ->orderBy('price', 'asc')
                ->get(),
            'instructors' => (clone $instructorsQuery)
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get(),
        ];

        return view('design_1.web.home.index', $data);
    }
}
