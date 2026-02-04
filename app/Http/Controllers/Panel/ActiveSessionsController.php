<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\UserActiveSession;
use App\Services\SessionManager;
use Illuminate\Http\Request;

class ActiveSessionsController extends Controller
{
    protected $sessionManager;

    public function __construct()
    {
        $this->sessionManager = new SessionManager();
    }

    /**
     * Display active sessions page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();
        
        $sessions = $this->sessionManager->getUserSessions($user->id);
        $currentSessionId = session()->getId();

        $data = [
            'pageTitle' => trans('panel.active_sessions'),
            'sessions' => $sessions,
            'currentSessionId' => $currentSessionId,
            'allowedDevices' => $user->allowed_devices ?? getGeneralSettings('login_device_limit') ?? 1,
        ];

        return view('web.default.panel.settings.active_sessions', $data);
    }

    /**
     * Terminate a specific session
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $user = auth()->user();

        // Verify the session belongs to the user
        $session = UserActiveSession::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$session) {
            $toastData = [
                'title' => trans('public.request_failed'),
                'msg' => trans('public.session_not_found'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }

        // Prevent terminating current session
        if ($session->session_id === session()->getId()) {
            $toastData = [
                'title' => trans('public.request_failed'),
                'msg' => trans('public.cannot_terminate_current_session'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }

        // Delete the session
        $deleted = $this->sessionManager->deleteSessionById($id, $user->id);

        if ($deleted) {
            // Decrement logged_count
            if ($user->logged_count > 0) {
                $user->update([
                    'logged_count' => $user->logged_count - 1
                ]);
            }

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('public.session_terminated_successfully'),
                'status' => 'success'
            ];
        } else {
            $toastData = [
                'title' => trans('public.request_failed'),
                'msg' => trans('public.failed_to_terminate_session'),
                'status' => 'error'
            ];
        }

        return back()->with(['toast' => $toastData]);
    }

    /**
     * Terminate all other sessions
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyOthers(Request $request)
    {
        $user = auth()->user();
        $currentSessionId = session()->getId();

        // Delete all sessions except current
        $deletedCount = UserActiveSession::where('user_id', $user->id)
            ->where('session_id', '!=', $currentSessionId)
            ->delete();

        // Update logged_count to 1 (only current session remains)
        $user->update(['logged_count' => 1]);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('public.all_other_sessions_terminated', ['count' => $deletedCount]),
            'status' => 'success'
        ];

        return back()->with(['toast' => $toastData]);
    }
    
    /**
     * Get session statistics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats()
    {
        $user = auth()->user();
        
        $totalSessions = $this->sessionManager->getActiveSessionsCount($user->id);
        $allowedDevices = $user->allowed_devices ?? getGeneralSettings('login_device_limit') ?? 1;
        
        $sessions = UserActiveSession::where('user_id', $user->id)->get();
        
        $webCount = $sessions->where('session_type', 'web')->count();
        $apiCount = $sessions->where('session_type', 'api')->count();

        return response()->json([
            'total_sessions' => $totalSessions,
            'allowed_devices' => $allowedDevices,
            'remaining_slots' => max(0, $allowedDevices - $totalSessions),
            'web_sessions' => $webCount,
            'api_sessions' => $apiCount,
            'can_login' => $totalSessions < $allowedDevices,
        ]);
    }
}
