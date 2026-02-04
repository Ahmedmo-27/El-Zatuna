<?php

namespace App\Services;

use App\Models\UserActiveSession;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class SessionManager
{
    /**
     * Create or update an active session record
     *
     * @param User $user
     * @param string $sessionId Laravel session ID or JWT token
     * @param string $sessionType 'web' or 'api'
     * @param Request|null $request
     * @param string|null $deviceFingerprint
     * @return UserActiveSession
     */
    public function createSession(User $user, string $sessionId, string $sessionType = 'web', $request = null, $deviceFingerprint = null)
    {
        $deviceData = $this->extractDeviceData($request);

        $sessionData = [
            'user_id' => $user->id,
            'session_id' => $sessionId,
            'session_type' => $sessionType,
            'device_fingerprint' => $deviceFingerprint,
            'device_name' => $deviceData['device_name'],
            'browser' => $deviceData['browser'],
            'os' => $deviceData['os'],
            'platform' => $deviceData['platform'],
            'ip_address' => $request ? $request->ip() : null,
            'user_agent' => $request ? $request->userAgent() : null,
            'last_activity' => time(),
            'created_at' => time(),
        ];

        // Check if session already exists (in case of token refresh)
        $session = UserActiveSession::where('session_id', $sessionId)->first();

        if ($session) {
            $session->update($sessionData);
        } else {
            $session = UserActiveSession::create($sessionData);
        }

        return $session;
    }

    /**
     * Update session activity timestamp
     *
     * @param string $sessionId
     * @return bool
     */
    public function updateActivity(string $sessionId)
    {
        return UserActiveSession::where('session_id', $sessionId)
            ->update(['last_activity' => time()]);
    }

    /**
     * Delete a specific session
     *
     * @param string $sessionId
     * @return bool
     */
    public function deleteSession(string $sessionId)
    {
        return UserActiveSession::where('session_id', $sessionId)->delete();
    }

    /**
     * Delete a specific session by ID
     *
     * @param int $sessionRecordId
     * @param int $userId
     * @return bool
     */
    public function deleteSessionById(int $sessionRecordId, int $userId)
    {
        return UserActiveSession::where('id', $sessionRecordId)
            ->where('user_id', $userId)
            ->delete();
    }

    /**
     * Delete all sessions for a user
     *
     * @param int $userId
     * @return int Number of deleted sessions
     */
    public function deleteAllUserSessions(int $userId)
    {
        return UserActiveSession::where('user_id', $userId)->delete();
    }

    /**
     * Get active sessions count for a user
     *
     * @param int $userId
     * @return int
     */
    public function getActiveSessionsCount(int $userId)
    {
        return UserActiveSession::where('user_id', $userId)->count();
    }

    /**
     * Get all active sessions for a user
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserSessions(int $userId)
    {
        return UserActiveSession::where('user_id', $userId)
            ->orderBy('last_activity', 'desc')
            ->get();
    }

    /**
     * Check if user has reached device limit
     *
     * @param User $user
     * @return bool
     */
    public function hasReachedLimit(User $user)
    {
        $activeCount = $this->getActiveSessionsCount($user->id);
        $allowedDevices = $user->allowed_devices ?? getGeneralSettings('login_device_limit') ?? 1;

        return $activeCount >= $allowedDevices;
    }

    /**
     * Sync logged_count with actual active sessions
     *
     * @param User $user
     * @return void
     */
    public function syncLoggedCount(User $user)
    {
        $actualCount = $this->getActiveSessionsCount($user->id);
        
        if ($user->logged_count != $actualCount) {
            $user->update(['logged_count' => $actualCount]);
        }
    }

    /**
     * Clean up expired/inactive sessions
     *
     * @param int $inactiveMinutes Sessions inactive for this many minutes will be removed
     * @return int Number of cleaned sessions
     */
    public function cleanupInactiveSessions(int $inactiveMinutes = 120)
    {
        $threshold = now()->subMinutes($inactiveMinutes)->timestamp;
        
        return UserActiveSession::where('last_activity', '<', $threshold)->delete();
    }

    /**
     * Extract device information from request
     *
     * @param $request
     * @return array
     */
    private function extractDeviceData($request)
    {
        if (!$request) {
            return [
                'device_name' => 'Unknown Device',
                'browser' => 'Unknown',
                'os' => 'Unknown',
                'platform' => 'desktop',
            ];
        }

        $userAgent = $request->userAgent();
        
        // Fallback if no user agent
        if (empty($userAgent)) {
            return [
                'device_name' => 'API Client',
                'browser' => 'API',
                'os' => 'Unknown',
                'platform' => 'desktop',
            ];
        }

        $agent = new Agent();
        $agent->setUserAgent($userAgent);

        // Extract browser info
        $browser = $agent->browser();
        $browserVersion = $agent->version($browser);
        $browserFull = !empty($browser) && $browser !== false 
            ? $browser . ($browserVersion ? " {$browserVersion}" : '') 
            : 'Unknown Browser';

        // Extract OS info
        $platform = $agent->platform();
        $platformVersion = $agent->version($platform);
        $osFull = !empty($platform) && $platform !== false 
            ? $platform . ($platformVersion ? " {$platformVersion}" : '') 
            : 'Unknown OS';

        // Determine device type
        $deviceType = 'desktop';
        if ($agent->isMobile()) {
            $deviceType = 'mobile';
        } elseif ($agent->isTablet()) {
            $deviceType = 'tablet';
        }

        // Build device name
        $deviceName = $agent->device();
        
        // Better fallback logic
        if (empty($deviceName) || $deviceName === 'WebKit' || $deviceName === false) {
            // Check if we have browser and OS
            if ($browserFull !== 'Unknown Browser' && $osFull !== 'Unknown OS') {
                $deviceName = $browserFull . ' on ' . $osFull;
            } elseif ($browserFull !== 'Unknown Browser') {
                $deviceName = $browserFull;
            } elseif ($osFull !== 'Unknown OS') {
                $deviceName = $osFull . ' Device';
            } else {
                // Last resort: parse user agent string
                $deviceName = $this->parseUserAgentForDevice($userAgent, $deviceType);
            }
        }

        return [
            'device_name' => $deviceName,
            'browser' => $browserFull,
            'os' => $osFull,
            'platform' => $deviceType,
        ];
    }

    /**
     * Fallback parser for user agent when Agent library fails
     *
     * @param string $userAgent
     * @param string $deviceType
     * @return string
     */
    private function parseUserAgentForDevice($userAgent, $deviceType)
    {
        // Check for common patterns
        if (stripos($userAgent, 'Postman') !== false) {
            return 'Postman API Client';
        }
        if (stripos($userAgent, 'curl') !== false) {
            return 'cURL';
        }
        if (stripos($userAgent, 'python') !== false) {
            return 'Python Client';
        }
        if (stripos($userAgent, 'java') !== false) {
            return 'Java Client';
        }
        if (stripos($userAgent, 'okhttp') !== false) {
            return 'Android App';
        }
        if (stripos($userAgent, 'alamofire') !== false || stripos($userAgent, 'cfnetwork') !== false) {
            return 'iOS App';
        }
        
        // Generic fallback
        return ucfirst($deviceType) . ' Device';
    }

    /**
     * Generate a unique session ID for API sessions
     *
     * @return string
     */
    public function generateSessionId()
    {
        return Str::random(64);
    }

    /**
     * Generate device fingerprint based on device characteristics
     *
     * @param $request
     * @return string SHA256 hash
     */
    public function generateDeviceFingerprint($request)
    {
        if (!$request) {
            return hash('sha256', 'unknown-device-' . time());
        }

        $userAgent = $request->userAgent() ?? 'unknown';
        $ip = $request->ip() ?? 'unknown';
        
        // For more stable fingerprint, we focus on device characteristics
        // IP can change (mobile networks, VPNs), so we don't make it mandatory
        // Browser name/version excluded to allow same device to use different browsers
        $agent = new Agent();
        $agent->setUserAgent($userAgent);

        $components = [
            $agent->platform(),    // OS: Windows, Mac, Linux, Android, iOS
            $agent->device(),      // Device model (if available)
            $agent->isMobile() ? 'mobile' : ($agent->isTablet() ? 'tablet' : 'desktop'),
            // Note: User agent still influences platform/device detection but isn't directly hashed
        ];

        $fingerprintString = implode('|', array_filter($components));
        
        return hash('sha256', $fingerprintString);
    }

    /**
     * Register a new device for a user
     *
     * @param User $user
     * @param string $deviceFingerprint
     * @param $request
     * @return \App\Models\UserRegisteredDevice
     */
    public function registerDevice(User $user, string $deviceFingerprint, $request = null)
    {
        $deviceData = $this->extractDeviceData($request);

        $device = \App\Models\UserRegisteredDevice::firstOrCreate(
            [
                'user_id' => $user->id,
                'device_fingerprint' => $deviceFingerprint,
            ],
            [
                'device_name' => $deviceData['device_name'],
                'browser' => $deviceData['browser'],
                'os' => $deviceData['os'],
                'platform' => $deviceData['platform'],
                'ip_address' => $request ? $request->ip() : null,
                'user_agent' => $request ? $request->userAgent() : null,
                'first_registered_at' => time(),
                'last_used_at' => time(),
                'is_trusted' => true,
                'login_count' => 1,
            ]
        );

        // If device already exists, update last used
        if (!$device->wasRecentlyCreated) {
            $device->updateLastUsed();
        }

        return $device;
    }

    /**
     * Check if device is registered for user
     *
     * @param int $userId
     * @param string $deviceFingerprint
     * @return bool
     */
    public function isDeviceRegistered(int $userId, string $deviceFingerprint)
    {
        return \App\Models\UserRegisteredDevice::where('user_id', $userId)
            ->where('device_fingerprint', $deviceFingerprint)
            ->exists();
    }

    /**
     * Get all registered devices for a user
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserRegisteredDevices(int $userId)
    {
        return \App\Models\UserRegisteredDevice::where('user_id', $userId)
            ->orderBy('last_used_at', 'desc')
            ->get();
    }

    /**
     * Check if user has any registered devices
     *
     * @param int $userId
     * @return bool
     */
    public function userHasRegisteredDevices(int $userId)
    {
        return \App\Models\UserRegisteredDevice::where('user_id', $userId)->exists();
    }
}
