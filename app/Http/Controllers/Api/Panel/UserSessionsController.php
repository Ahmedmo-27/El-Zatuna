<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Api\Controller;
use App\Models\Api\UserFirebaseSessions;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class UserSessionsController extends Controller
{
    /**
     * Get all active sessions for the authenticated user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $user = apiAuth();

            if (!$user) {
                return apiResponse2(0, 'unauthorized', trans('auth.not_login'));
            }

            // Get all active sessions for the user
            $sessions = UserFirebaseSessions::where('user_id', $user->id)
                ->orderBy('updated_at', 'desc')
                ->get();

            $currentToken = $request->bearerToken();
            $sessionData = [];

            foreach ($sessions as $session) {
                $sessionData[] = [
                    'id' => $session->id,
                    'device' => $this->getDeviceInfo($session),
                    'ip_address' => $session->ip,
                    'last_active' => $session->updated_at->toIso8601String(),
                    'is_current' => $session->token === $currentToken,
                ];
            }

            return apiResponse2(1, 'retrieved', trans('public.retrieved'), [
                'sessions' => $sessionData,
            ]);

        } catch (\Exception $e) {
            return apiResponse2(0, 'error', $e->getMessage());
        }
    }

    /**
     * Revoke a specific session
     *
     * @param Request $request
     * @param int $sessionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $sessionId)
    {
        try {
            $user = apiAuth();

            if (!$user) {
                return apiResponse2(0, 'unauthorized', trans('auth.not_login'));
            }

            // Find the session
            $session = UserFirebaseSessions::where('id', $sessionId)
                ->where('user_id', $user->id)
                ->first();

            if (!$session) {
                return apiResponse2(0, 'not_found', trans('public.not_found'));
            }

            // Check if trying to delete current session
            $currentToken = $request->bearerToken();
            if ($session->token === $currentToken) {
                return apiResponse2(0, 'cannot_delete_current_session', trans('auth.cannot_delete_current_session'));
            }

            // Delete the session
            $session->delete();

            // Update logged count
            $user->update([
                'logged_count' => max(0, $user->logged_count - 1)
            ]);

            return apiResponse2(1, 'session_revoked', trans('auth.session_revoked'));

        } catch (\Exception $e) {
            return apiResponse2(0, 'error', $e->getMessage());
        }
    }

    /**
     * Get device information from session
     *
     * @param UserFirebaseSessions $session
     * @return string
     */
    private function getDeviceInfo($session)
    {
        if (empty($session->user_agent)) {
            return 'Unknown Device';
        }

        $agent = new Agent();
        $agent->setUserAgent($session->user_agent);

        $browser = $agent->browser();
        $platform = $agent->platform();

        if ($agent->isDesktop()) {
            return "{$browser} on {$platform}";
        } elseif ($agent->isMobile()) {
            $device = $agent->device();
            return "{$browser} on {$device}";
        } elseif ($agent->isTablet()) {
            $device = $agent->device();
            return "{$browser} on {$device} Tablet";
        }

        return "{$browser} on {$platform}";
    }
}
