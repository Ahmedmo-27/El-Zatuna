<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use Illuminate\Http\Request;

class TutorPayoutsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('admin_payouts_list');

        $status = $request->get('status');

        $query = Payout::query()->whereNotNull('tutor_id');

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $payouts = $query->with(['tutor.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $data = [
            'pageTitle' => trans('update.tutor_payouts'),
            'payouts' => $payouts,
            'status' => $status,
        ];

        return view('admin.financial.tutor_payouts.index', $data);
    }

    public function markPaid($id)
    {
        $this->authorize('admin_payouts_list');

        $payout = Payout::whereNotNull('tutor_id')->findOrFail($id);

        $payout->update([
            'status' => Payout::$paid,
            'paid_at' => time(),
        ]);

        return back();
    }
}
