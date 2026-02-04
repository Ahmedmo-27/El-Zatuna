<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\Setting;
use Illuminate\Http\Request;

class TutorPayoutsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('panel_financial_payout');

        $user = auth()->user();
        $tutor = $user->getOrCreateTutor();

        $payouts = Payout::query()
            ->where('tutor_id', $tutor->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $data = [
            'pageTitle' => trans('update.tutor_payouts'),
            'payouts' => $payouts,
            'payoutBalance' => $tutor->payout_balance,
            'minimumPayout' => Setting::getFinancialSettings('minimum_payout') ?? 0,
        ];

        return view('design_1.panel.financial.tutor_payouts.index', $data);
    }

    public function requestPayout(Request $request)
    {
        $this->authorize('panel_financial_payout');

        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = auth()->user();
        $tutor = $user->getOrCreateTutor();

        $amount = (float)$request->input('amount');
        $minimumPayout = (float)(Setting::getFinancialSettings('minimum_payout') ?? 0);

        if ($amount > $tutor->payout_balance || ($minimumPayout > 0 && $amount < $minimumPayout)) {
            return back();
        }

        Payout::create([
            'user_id' => $user->id,
            'tutor_id' => $tutor->id,
            'amount' => $amount,
            'account_name' => $user->full_name,
            'account_number' => $user->email,
            'account_bank_name' => 'Tutor Payout',
            'status' => Payout::$pending,
            'created_at' => time(),
        ]);

        $tutor->update([
            'payout_balance' => $tutor->payout_balance - $amount,
            'updated_at' => time(),
        ]);

        return back();
    }
}
