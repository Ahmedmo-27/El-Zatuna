<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Controller;
use App\Mixins\Logs\UserLoginHistoryMixin;
use App\Models\Api\UserFirebaseSessions;
use App\Services\SessionManager;
use App\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];

        validateParam($request->all(), $rules);

        return $this->attemptLogin($request);

    }

    public function username()
    {
        return 'email';
    }

    protected function attemptLogin(Request $request)
    {
        $credentials = [
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ];


        if (!$token = auth('api')->attempt($credentials)) {
            return apiResponse2(0, 'incorrect', trans('auth.incorrect'));
        }
        return $this->afterLogged($request, $token);
    }

    public function afterLogged(Request $request, $token, $verify = false)
    {
        $user = auth('api')->user();

        if ($user->ban) {
            $time = time();
            $endBan = $user->ban_end_at;
            if (!empty($endBan) and $endBan > $time) {
                auth('api')->logout();
                return apiResponse2(0, 'banned_account', trans('auth.banned_account'));
            } elseif (!empty($endBan) and $endBan < $time) {
                $user->update([
                    'ban' => false,
                    'ban_start_at' => null,
                    'ban_end_at' => null,
                ]);
            }

        }

        if ($user->status != User::$active and !$verify) {
            // auth('api')->logout();
            auth('api')->logout();
            //  dd(apiAuth());
            $verificationController = new VerificationController();
            $checkConfirmed = $verificationController->checkConfirmed($user, 'email', $request->input('email'));

            if ($checkConfirmed['status'] == 'send') {

                return apiResponse2(0, 'not_verified', trans('api.auth.not_verified'));

            } elseif ($checkConfirmed['status'] == 'verified') {
                $user->update([
                    'status' => User::$active,
                ]);
            }
        } elseif ($verify) {
            $user->update([
                'status' => User::$active,
            ]);

        }

        if ($user->status != User::$active) {
            \auth('api')->logout();
            return apiResponse2(0, 'inactive_account', trans('auth.inactive_account'));
        }
        
        // Check device limit BEFORE device registration
        $checkLoginDeviceLimit = $this->checkLoginDeviceLimit($user);
        if ($checkLoginDeviceLimit != "ok") {
            \auth('api')->logout();
            return apiResponse2(0, 'limit_account', trans('auth.limit_account'));
        }

        // Generate device fingerprint
        $sessionManager = new SessionManager();
        $deviceFingerprint = $sessionManager->generateDeviceFingerprint($request);
        
        // Check if current device is already registered
        $isDeviceRegistered = $sessionManager->isDeviceRegistered($user->id, $deviceFingerprint);
        $hasRegisteredDevices = $sessionManager->userHasRegisteredDevices($user->id);
        
        if ($isDeviceRegistered) {
            // Device is ALREADY registered - check if it's trusted
            $device = \App\Models\UserRegisteredDevice::where('user_id', $user->id)
                ->where('device_fingerprint', $deviceFingerprint)
                ->first();
                
            if ($device && !$device->is_trusted) {
                // Device exists but is not trusted - BLOCK login
                \auth('api')->logout();
                return apiResponse2(0, 'device_not_trusted', trans('auth.device_not_trusted_please_contact_support'));
            }
            // Device is registered and trusted - proceed with login (no limit check needed)
        } else {
            // Device NOT registered - check if user has reached the registered devices limit
            $registeredDevicesCount = \App\Models\UserRegisteredDevice::where('user_id', $user->id)->count();
            $allowedDevices = $user->allowed_devices ?? getGeneralSettings('login_device_limit') ?? 1;
            
            if ($registeredDevicesCount >= $allowedDevices) {
                // User has reached max registered devices limit - BLOCK login
                \auth('api')->logout();
                return apiResponse2(0, 'registered_devices_limit_reached', trans('auth.registered_devices_limit_reached', ['limit' => $allowedDevices]));
            }
            // Under limit - will register new device below
        }

        // Register or update device
        $registeredDevice = $sessionManager->registerDevice($user, $deviceFingerprint, $request);

        // Generate unique session token for this device/session
        $sessionToken = $sessionManager->generateSessionId();

        // Build response data
        $profile_completion = [];
        $data = [
            'account_token' => $token,  // JWT for account authentication
            'session_token' => $sessionToken,  // Unique token for this device/session
            'device_fingerprint' => $deviceFingerprint,  // Device identifier
            'is_first_device' => !$hasRegisteredDevices,  // True if this is the first device registered
            'token_type' => 'bearer',
            'user_id' => $user->id,
        ];
        
        if (!$user->full_name) {
            $profile_completion[] = 'full_name';
            $data['profile_completion'] = $profile_completion;
        }

        // Store in Firebase sessions (for backward compatibility)
        UserFirebaseSessions::create([
            "user_id" => $user->id,
            "token" => $token,
            "ip" => $request->getClientIp(),
            "fcm_token" => "",
            "user_agent" => $request->userAgent(),
        ]);
        
        // Create active session record with session_token and device_fingerprint
        $sessionManager->createSession($user, $sessionToken, 'api', $request, $deviceFingerprint);
        
        $userLoginHistoryMixin = new UserLoginHistoryMixin();
        $userLoginHistoryMixin->storeUserLoginHistory($user);
        $user->update([
            'logged_count' => $user->logged_count + 1
        ]);

        return apiResponse2(1, 'login', trans('auth.login'), $data);


    }

    public function logout(Request $request)
    {
        $user = auth('api')->user();
        
        // Get session token from request (optional - for specific session logout)
        $sessionToken = $request->input('session_token');
        
        auth('api')->logout();
        
        if (!apiAuth()) {
            $user->update([
                'logged_count' => max(0, $user->logged_count - 1)
            ]);
            
            // Delete from Firebase sessions
            $session = UserFirebaseSessions::where('token', $user->token)->first();
            if ($session) {
                $session->delete();
            }
            
            // Delete the specific session if session_token provided
            $sessionManager = new SessionManager();
            if ($sessionToken) {
                // Delete by specific session token
                $sessionManager->deleteSession($sessionToken);
            } else {
                // Fallback: delete the most recent API session for this user
                $apiSession = \App\Models\UserActiveSession::where('user_id', $user->id)
                    ->where('session_type', 'api')
                    ->orderBy('last_activity', 'desc')
                    ->first();
                if ($apiSession) {
                    $sessionManager->deleteSession($apiSession->session_id);
                }
            }
            
            $userLoginHistoryMixin = new UserLoginHistoryMixin();
            $userLoginHistoryMixin->storeUserLogoutHistory($user);
            return apiResponse2(1, 'logout', trans('auth.logout'));
        }
        return apiResponse2(0, 'failed', trans('auth.logout.failed'));
    }
    private function checkLoginDeviceLimit($user)
    {
        // Check if user has a custom allowed_devices value
        $limitCount = !empty($user->allowed_devices) ? $user->allowed_devices : 1;

        // Fallback to global security settings if needed
        $securitySettings = getGeneralSecuritySettings();
        if (!empty($securitySettings) and !empty($securitySettings['login_device_limit'])) {
            // If user's allowed_devices is not set or is 1 (default), use global setting
            if (empty($user->allowed_devices) || $user->allowed_devices == 1) {
                $limitCount = !empty($securitySettings['number_of_allowed_devices']) ? $securitySettings['number_of_allowed_devices'] : 1;
            }
        }

        $count = $user->logged_count;

        if ($count >= $limitCount) {
            return "no";
        }

        return 'ok';
    }


}
