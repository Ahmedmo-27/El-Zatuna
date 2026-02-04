<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mixins\LandingBuilder\FrontComponentsDataMixins;
use App\Models\Role;
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

        $seoSettings = getSeoMetas('home');
        $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('home.home_title');
        $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('home.home_title');
        $pageRobot = getPageRobot('home');

        $discountedCourses = $frontComponentsDataMixins->getDiscountedCoursesData(3);
        if ($discountedCourses->isEmpty()) {
            $discountedCourses = Webinar::query()
                ->where('status', Webinar::$active)
                ->where('private', false)
                ->whereNotNull('price')
                ->where('price', '>', 0)
                ->with('teacher')
                ->orderBy('updated_at', 'desc')
                ->limit(3)
                ->get();
        }

        $upcomingCourses = $frontComponentsDataMixins->getUpcomingCoursesData(3);
        if ($upcomingCourses->isEmpty()) {
            $upcomingCourses = Webinar::query()
                ->where('status', Webinar::$active)
                ->where('private', false)
                ->where('type', Webinar::$course)
                ->with('teacher')
                ->orderBy('updated_at', 'desc')
                ->limit(3)
                ->get();
        }

        $freeCourses = $frontComponentsDataMixins->getFreeCoursesData(3);
        if ($freeCourses->isEmpty()) {
            $freeCourses = Webinar::query()
                ->where('status', Webinar::$active)
                ->where('private', false)
                ->where(function ($query) {
                    $query->whereNull('price')->orWhere('price', 0);
                })
                ->with('teacher')
                ->orderBy('updated_at', 'desc')
                ->limit(3)
                ->get();
        }

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'activeTheme' => $activeTheme,
            'homeLanding' => $homeLanding,
            'discountedCourses' => $discountedCourses,
            'upcomingCourses' => $upcomingCourses,
            'freeCourses' => $freeCourses,
            'instructors' => User::query()
                ->where('role_name', Role::$teacher)
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get(),
        ];

        return view('design_1.web.home.index', $data);
    }
}
