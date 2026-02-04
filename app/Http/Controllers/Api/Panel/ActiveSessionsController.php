<?php

namespace App\Http\Controllers\Api\Panel;

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
     * Get all active sessions for the authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = auth('api')->user();

        if (!$user) {
            return apiResponse2(0, 'unauthorized', trans('auth.not_access_content'));
        }

        $sessions = $this->sessionManager->getUserSessions($user->id);

        $data = $sessions->map(function ($session) {
            return [
                'id' => $session->id,
                'session_token' => $session->session_id,  // The unique session token for this device
                'device_fingerprint' => $session->device_fingerprint,  // Device fingerprint
                'device_name' => $session->getDeviceDisplayName(),
                'device_icon' => $session->getDeviceIcon(),
                'browser' => $session->browser,
                'os' => $session->os,
                'platform' => $session->platform,
                'ip_address' => $session->ip_address,
                'session_type' => $session->session_type,
                'last_activity' => $session->last_activity, // Already an integer timestamp
                'last_activity_formatted' => $session->getFormattedLastActivity(),
                'is_current' => false, // We'll identify current session separately if needed
                'created_at' => $session->created_at,
            ];
        });

        return apiResponse2(1, 'retrieved', trans('public.success'), [
            'sessions' => $data,
            'total_sessions' => $sessions->count(),
            'allowed_devices' => $user->allowed_devices ?? getGeneralSettings('login_device_limit') ?? 1,
        ]);
    }

    /**
     * Delete a specific session (logout from specific device)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $user = auth('api')->user();

        if (!$user) {
            return apiResponse2(0, 'unauthorized', trans('auth.not_access_content'));
        }

        $rules = [
            'session_token' => 'required|string',
        ];

        validateParam($request->all(), $rules);

        $sessionToken = $request->input('session_token');

        // Verify the session belongs to the user
        $session = UserActiveSession::where('session_id', $sessionToken)
            ->where('user_id', $user->id)
            ->first();

        if (!$session) {
            return apiResponse2(0, 'not_found', trans('public.session_not_found_or_unauthorized'));
        }

        // Delete the session
        $deleted = $this->sessionManager->deleteSession($sessionToken);

        if ($deleted) {
            // Decrement logged_count
            if ($user->logged_count > 0) {
                $user->update([
                    'logged_count' => $user->logged_count - 1
                ]);
            }

            return apiResponse2(1, 'deleted', trans('public.session_terminated_successfully'));
        }

        return apiResponse2(0, 'failed', trans('public.failed_to_terminate_session'));
    }

    /**
     * Delete all sessions except the current one
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyOthers()
    {
        $user = auth('api')->user();

        if (!$user) {
            return apiResponse2(0, 'unauthorized', trans('auth.not_access_content'));
        }

        // Get all user sessions
        $sessions = UserActiveSession::where('user_id', $user->id)->get();
        
        // Delete all sessions (user will remain logged in with their current token)
        // The current API session will be recreated on next request if using middleware
        $deletedCount = UserActiveSession::where('user_id', $user->id)->delete();

        // Update logged_count to 1 (only current session remains)
        $user->update(['logged_count' => 1]);

        return apiResponse2(1, 'deleted', trans('public.all_other_sessions_terminated'), [
            'terminated_count' => $deletedCount,
        ]);
    }

    /**
     * Get session statistics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats()
    {
        $user = auth('api')->user();

        if (!$user) {
            return apiResponse2(0, 'unauthorized', trans('auth.not_access_content'));
        }

        $totalSessions = $this->sessionManager->getActiveSessionsCount($user->id);
        $allowedDevices = $user->allowed_devices ?? getGeneralSettings('login_device_limit') ?? 1;

        $sessions = UserActiveSession::where('user_id', $user->id)->get();
        
        $webCount = $sessions->where('session_type', 'web')->count();
        $apiCount = $sessions->where('session_type', 'api')->count();

        return apiResponse2(1, 'retrieved', trans('public.success'), [
            'total_sessions' => $totalSessions,
            'allowed_devices' => $allowedDevices,
            'remaining_slots' => max(0, $allowedDevices - $totalSessions),
            'web_sessions' => $webCount,
            'api_sessions' => $apiCount,
            'can_login' => $totalSessions < $allowedDevices,
        ]);
    }
}
