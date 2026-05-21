<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveSessionActivityLog;
use App\User;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Admin Live Session Logs",
 *     description="Admin live-session activity logs"
 * )
 */

class LiveSessionLogController extends Controller
{
    /**
     * List live-session activity logs.
     *
     * @OA\Get(
     *     path="/admin/live-sessions/logs",
     *     summary="List live session logs",
     *     tags={"Admin Live Session Logs"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Activity logs list")
     * )
     */
    public function index(Request $request)
    {
        $query = LiveSessionActivityLog::with(['liveSession', 'user'])
            ->orderByDesc('created_at');

        if ($request->filled('live_session_id')) {
            $query->where('live_session_id', $request->input('live_session_id'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        $logs = $query->paginate(25);
        $users = User::orderBy('id', 'desc')->limit(200)->get();

        return view('admin.live-sessions.logs', compact('logs', 'users'));
    }
}