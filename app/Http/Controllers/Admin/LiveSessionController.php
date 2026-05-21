<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveSession;
use App\Services\LiveSessionManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LiveSessionController extends Controller
{
    /** List all live sessions for admin overview */
    public function index()
    {
        $sessions = LiveSession::with('teacher')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.live-sessions.index', compact('sessions'));
    }

    /** Show details and analytics for a session */
    public function show($id)
    {
        $session = LiveSession::with(['teacher', 'bookings.student'])
            ->findOrFail($id);
        return view('admin.live-sessions.show', compact('session'));
    }

    /** Cancel a session (admin) */
    public function cancel($id)
    {
        $session = LiveSession::findOrFail($id);
        app(LiveSessionManagementService::class)->cancel($session);
        return Redirect::back()->with('success', 'Session cancelled.');
    }

    /** Override capacity (admin) */
    public function overrideCapacity(Request $request, $id)
    {
        $request->validate(['max_students' => 'required|integer|min:1']);
        $session = LiveSession::findOrFail($id);
        $session->max_students = $request->input('max_students');
        $session->save();
        return Redirect::back()->with('success', 'Capacity overridden.');
    }
}
