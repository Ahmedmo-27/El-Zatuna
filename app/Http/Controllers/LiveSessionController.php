<?php

namespace App\Http\Controllers;

use App\Models\LiveSessionActivityLog;
use App\Models\LiveSession;
use App\Models\LiveSessionBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\LiveSessionJoinService;

/**
 * @OA\Tag(
 *     name="Live Sessions",
 *     description="Public live session pages"
 * )
 */

class LiveSessionController extends Controller
{
    /**
     * Browse live sessions.
     *
     * @OA\Get(
     *     path="/live-sessions",
     *     summary="List live sessions",
     *     tags={"Live Sessions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="university_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="faculty_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="upcoming", in="query", required=false, @OA\Schema(type="boolean")),
     *     @OA\Response(response=200, description="Live sessions index")
     * )
     */
    // Student public listing
    public function index(Request $request)
    {
        $query = LiveSession::where('status', 'published')
            ->where('start_at', '>', now());

        $user = $request->user('web') ?? $request->user('api') ?? \Illuminate\Support\Facades\Auth::user() ?? $request->user();
        $userUniversityId = $user ? $user->university_id : null;
        $userFacultyId = $user ? $user->faculty_id : null;

        if (!$user || !$user->isAdmin()) {
            $query->where(function($q) use ($userUniversityId) {
                $q->whereNull('university_id');
                if ($userUniversityId) {
                    $q->orWhere('university_id', $userUniversityId);
                }
            });

            $query->where(function($q) use ($userFacultyId) {
                $q->whereNull('faculty_id');
                if ($userFacultyId) {
                    $q->orWhere('faculty_id', $userFacultyId);
                }
            });
        }

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

    /**
     * Show my booked sessions.
     *
     * @OA\Get(
     *     path="/live-sessions/me",
     *     summary="My booked live sessions",
     *     tags={"Live Sessions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="My sessions")
     * )
     */
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

    /**
     * Show one live session.
     *
     * @OA\Get(
     *     path="/live-sessions/{id}",
     *     summary="Show live session",
     *     tags={"Live Sessions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Live session details")
     * )
     */
    // Detail page
    public function show($id)
    {
        $session = LiveSession::with('teacher')->findOrFail($id);
        
        // Let policy handle authorization. Guests will be denied if policy requires User, 
        // but we can pass optional user to policy if we want guests to view.
        // Wait, Laravel policy methods return false if user is null unless type is ?User.
        // I will just use authorize, if it throws, tests will need to expect it or we update policy.
        $this->authorize('view', $session);
        $user = Auth::user();
        $booking = null;
        if ($user) {
            $booking = LiveSessionBooking::where('student_id', $user->id)
                ->where('live_session_id', $session->id)
                ->first();
        }
        return view('live-sessions.show', compact('session', 'booking'));
    }

    /**
     * Join a live session.
     *
     * @OA\Get(
     *     path="/live-sessions/{id}/join",
     *     summary="Join live session",
     *     tags={"Live Sessions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=302, description="Redirect to provider join URL"),
     *     @OA\Response(response=200, description="Join fallback page")
     * )
     */
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
