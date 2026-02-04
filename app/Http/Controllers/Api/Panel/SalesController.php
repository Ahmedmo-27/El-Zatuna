<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Api\Controller;
use App\Models\Api\Sale;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $user = apiAuth();
        $baseQuery = Sale::where('seller_id', $user->id)
            ->whereNull('refund_at');

        $studentIds = deepClone($baseQuery)->pluck('buyer_id')->toArray();
       
        $getStudentCount = count($studentIds);
        $getWebinarsCount = count(array_filter(deepClone($baseQuery)->pluck('webinar_id')->toArray()));
        $getMeetingCount = count(array_filter(deepClone($baseQuery)->pluck('meeting_id')->toArray()));

        $query = deepClone($baseQuery)->handleFilters();
        
        $paginatedData = apiPagination(
            $query,
            $request,
            function ($sale) {
                return $sale->details;
            },
            url('/api/' . config('app.api_version', 'development') . '/panel/financial/sales')
        );

        return apiResponse2(1, 'retrieved', trans('public.retrieved'), [
            'sales' => $paginatedData['items'],
            'pagination' => $paginatedData['pagination'],
            'students_count' => $getStudentCount,
            'webinars_count' => $getWebinarsCount,
            'meetings_count' => $getMeetingCount,
            'total_sales' => $user->getSaleAmounts(),
            'class_sales' => $user->classesSaleAmount(),
            'meeting_sales' => $user->meetingsSaleAmount()
        ]);
    }
 
}
