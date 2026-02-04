<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseRevenue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TutorEarningsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('admin_sales_list');

        $query = CourseRevenue::query()
            ->select([
                'course_id',
                'tutor_id',
                DB::raw('count(*) as sales_count'),
                DB::raw('sum(amount) as gross_amount'),
                DB::raw('sum(platform_fee) as platform_fee'),
                DB::raw('sum(tutor_earnings) as tutor_earnings'),
            ])
            ->groupBy('course_id', 'tutor_id');

        $revenues = $query->with(['course', 'tutor.user'])
            ->orderBy('gross_amount', 'desc')
            ->paginate(20);

        $data = [
            'pageTitle' => trans('update.tutor_earnings'),
            'revenues' => $revenues,
            'platformRevenue' => CourseRevenue::query()->sum('platform_fee'),
            'tutorRevenue' => CourseRevenue::query()->sum('tutor_earnings'),
        ];

        return view('admin.financial.tutor_earnings.index', $data);
    }
}
