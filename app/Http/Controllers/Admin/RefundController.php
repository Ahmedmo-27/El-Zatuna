<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ExecuteSaleRefund;
use App\Models\LiveSessionActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('sale_refunds')
            ->leftJoin('sales', 'sales.id', '=', 'sale_refunds.sale_id')
            ->select(
                'sale_refunds.*',
                'sales.live_session_id',
                'sales.buyer_id',
                'sales.order_id',
                'sales.total_amount as sale_total_amount'
            )
            ->orderByDesc('sale_refunds.created_at');

        if ($request->filled('status')) {
            $query->where('sale_refunds.status', $request->input('status'));
        }

        if ($request->filled('sale_id')) {
            $query->where('sale_refunds.sale_id', $request->input('sale_id'));
        }

        $refunds = $query->paginate(20);

        return view('admin.refunds.index', compact('refunds'));
    }

    public function retry($id)
    {
        $refund = DB::table('sale_refunds')->where('id', $id)->first();

        if (!$refund) {
            return back()->with('error', 'Refund record not found.');
        }

        DB::table('sale_refunds')->where('id', $id)->update([
            'status' => 'pending',
            'last_error_message' => null,
            'updated_at' => now(),
        ]);

        ExecuteSaleRefund::dispatch((int) $id);

        $sale = DB::table('sales')->where('id', $refund->sale_id)->first();
        if (!empty($sale) && !empty($sale->live_session_id)) {
            LiveSessionActivityLog::log(
                (int) $sale->live_session_id,
                $sale->buyer_id ?? null,
                'refund_retry_triggered',
                'Refund retry dispatched from admin panel',
                ['refund_id' => (int) $id, 'sale_id' => (int) $refund->sale_id]
            );
        }

        return back()->with('success', 'Refund retry dispatched.');
    }
}