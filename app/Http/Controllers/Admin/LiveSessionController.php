<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveSession;
use App\Services\LiveSessionManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

/**
 * @OA\Tag(
 *     name="Admin Live Sessions",
 *     description="Admin live-session oversight pages"
 * )
 */

class LiveSessionController extends Controller
{
    /**
     * List all live sessions for admin overview.
     *
     * @OA\Get(
     *     path="/admin/live-sessions",
     *     summary="List admin live sessions",
     *     tags={"Admin Live Sessions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Admin live sessions list")
     * )
     */
    public function index()
    {
        $sessions = LiveSession::with('teacher')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.live-sessions.index', compact('sessions'));
    }

    /**
     * Show details and analytics for a session.
     *
     * @OA\Get(
     *     path="/admin/live-sessions/{id}",
     *     summary="Show admin live session",
     *     tags={"Admin Live Sessions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Admin live session details")
     * )
     */
    public function show($id)
    {
        $session = LiveSession::with(['teacher', 'bookings.student'])
            ->findOrFail($id);
        return view('admin.live-sessions.show', compact('session'));
    }

    /**
     * Cancel a session (admin).
     *
     * @OA\Post(
     *     path="/admin/live-sessions/{id}/cancel",
     *     summary="Cancel live session",
     *     tags={"Admin Live Sessions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=302, description="Redirect after cancel")
     * )
     */
    public function cancel($id)
    {
        $session = LiveSession::findOrFail($id);
        app(LiveSessionManagementService::class)->cancel($session);
        return Redirect::back()->with('success', 'Session cancelled.');
    }

    /**
     * Override capacity (admin).
     *
     * @OA\Post(
     *     path="/admin/live-sessions/{id}/override-capacity",
     *     summary="Override live session capacity",
     *     tags={"Admin Live Sessions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=302, description="Redirect after override")
     * )
     */
    public function overrideCapacity(Request $request, $id)
    {
        $request->validate(['max_students' => 'required|integer|min:1']);
        $session = LiveSession::findOrFail($id);
        $session->max_students = $request->input('max_students');
        $session->save();
        return Redirect::back()->with('success', 'Capacity overridden.');
    }
}
