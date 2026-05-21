<?php

namespace App\Http\Controllers;

use App\Models\LiveSessionActivityLog;
use App\Models\LiveSession;
use App\Models\LiveSessionBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\LiveSessionJoinService;

class LiveSessionController extends Controller
{
    // Student public listing
    public function index(Request $request)
    {
        $query = LiveSession::where('status', 'published')
            ->where('start_at', '>', now());

        if ($request->filled('university_id')) {
            $query->where('university_id', $request->university_id);
        }
        if ($request->filled('faculty_id')) {
            $query->where('faculty_id', $request->faculty_id);
        }
        if ($request->has('upcoming')) {
            $query->where('start_at', '>', now());
        }

        $sessions = $query->paginate(12);

        return view('live-sessions.index', compact('sessions'));
    }

    // Student dashboard – my booked sessions
    public function mySessions()
    {
        $user = Auth::user();
        $bookings = LiveSessionBooking::with('liveSession')
            ->where('student_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('live-sessions.my', compact('bookings'));
    }

    // Detail page
    public function show($id)
    {
        $session = LiveSession::with('teacher')->findOrFail($id);
        $user = Auth::user();
        $booking = null;
        if ($user) {
            $booking = LiveSessionBooking::where('student_id', $user->id)
                ->where('live_session_id', $session->id)
                ->first();
        }
        return view('live-sessions.show', compact('session', 'booking'));
    }

    // Secure join endpoint – backend decides eligibility
    public function join($id, LiveSessionJoinService $joinService)
    {
        $session = LiveSession::findOrFail($id);
        $user = Auth::user();
        if ($joinService->canJoin($user, $session)) {
            $url = $joinService->getProviderUrl($session, $user);
            return redirect()->away($url);
        }

        LiveSessionActivityLog::log(
            $session->id,
            $user->id ?? null,
            'join_attempt',
            'Join denied by access rules',
            ['status' => $session->status]
        );
        return view('live-sessions.join-fallback', compact('session'));
    }
}
?>
