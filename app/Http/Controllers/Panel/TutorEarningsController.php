<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\CourseRevenue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TutorEarningsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('panel_financial_sales_reports');

        $user = auth()->user();
        $tutor = $user->getOrCreateTutor();

        $revenues = CourseRevenue::query()
            ->select([
                'course_id',
                DB::raw('count(*) as sales_count'),
                DB::raw('sum(amount) as gross_amount'),
                DB::raw('sum(platform_fee) as platform_fee'),
                DB::raw('sum(tutor_earnings) as tutor_earnings'),
            ])
            ->where('tutor_id', $tutor->id)
            ->groupBy('course_id')
            ->with(['course'])
            ->orderBy('gross_amount', 'desc')
            ->paginate(20);

        $data = [
            'pageTitle' => trans('update.tutor_earnings'),
            'revenues' => $revenues,
            'payoutBalance' => $tutor->payout_balance,
        ];

        return view('design_1.panel.financial.tutor_earnings.index', $data);
    }
}
