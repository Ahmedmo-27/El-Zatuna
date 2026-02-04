<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Controller;
use App\Models\UserRegisteredDevice;
use App\Services\SessionManager;
use Illuminate\Http\Request;

class RegisteredDevicesController extends Controller
{
    protected $sessionManager;

    public function __construct()
    {
        $this->sessionManager = new SessionManager();
    }

    /**
     * Get all registered devices for the authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = auth('api')->user();

        if (!$user) {
            return apiResponse2(0, 'unauthorized', trans('auth.not_access_content'));
        }

        $devices = UserRegisteredDevice::where('user_id', $user->id)
            ->orderBy('last_used_at', 'desc')
            ->get();

        $data = $devices->map(function ($device) {
            return [
                'id' => $device->id,
                'device_fingerprint' => $device->device_fingerprint,
                'device_name' => $device->device_name,
                'browser' => $device->browser,
                'os' => $device->os,
                'platform' => $device->platform,
                'ip_address' => $device->ip_address,
                'first_registered_at' => $device->first_registered_at,
                'last_used_at' => $device->last_used_at,
                'is_trusted' => $device->is_trusted,
                'login_count' => $device->login_count,
                'first_registered_formatted' => dateTimeFormat($device->first_registered_at, 'j M Y H:i'),
                'last_used_formatted' => $device->last_used_at ? dateTimeFormat($device->last_used_at, 'j M Y H:i') : null,
            ];
        });

        return apiResponse2(1, 'retrieved', trans('public.success'), [
            'devices' => $data,
            'total_devices' => $devices->count(),
        ]);
    }

    /**
     * Update device trust status
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTrust(Request $request, $id)
    {
        $user = auth('api')->user();

        if (!$user) {
            return apiResponse2(0, 'unauthorized', trans('auth.not_access_content'));
        }

        $rules = [
            'is_trusted' => 'required|boolean',
        ];

        validateParam($request->all(), $rules);

        $device = UserRegisteredDevice::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$device) {
            return apiResponse2(0, 'not_found', trans('public.device_not_found'));
        }

        $device->update([
            'is_trusted' => $request->input('is_trusted')
        ]);

        $action = $request->input('is_trusted') ? 'trusted' : 'untrusted';

        return apiResponse2(1, 'updated', trans('public.device_' . $action . '_successfully'), [
            'device' => [
                'id' => $device->id,
                'device_name' => $device->device_name,
                'is_trusted' => $device->is_trusted,
            ]
        ]);
    }

    /**
     * Remove a registered device (requires admin action or verification in production)
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $user = auth('api')->user();

        if (!$user) {
            return apiResponse2(0, 'unauthorized', trans('auth.not_access_content'));
        }

        $device = UserRegisteredDevice::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$device) {
            return apiResponse2(0, 'not_found', trans('public.device_not_found'));
        }

        // Check if this is the last device
        $deviceCount = UserRegisteredDevice::where('user_id', $user->id)->count();
        
        if ($deviceCount <= 1) {
            return apiResponse2(0, 'cannot_delete_last_device', trans('public.cannot_delete_last_registered_device'));
        }

        // Delete device
        $device->delete();

        return apiResponse2(1, 'deleted', trans('public.device_removed_successfully'));
    }

    /**
     * Get statistics about registered devices
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats()
    {
        $user = auth('api')->user();

        if (!$user) {
            return apiResponse2(0, 'unauthorized', trans('auth.not_access_content'));
        }

        $devices = UserRegisteredDevice::where('user_id', $user->id)->get();
        
        $trustedCount = $devices->where('is_trusted', true)->count();
        $untrustedCount = $devices->where('is_trusted', false)->count();

        $platformStats = [
            'mobile' => $devices->where('platform', 'mobile')->count(),
            'tablet' => $devices->where('platform', 'tablet')->count(),
            'desktop' => $devices->where('platform', 'desktop')->count(),
        ];

        return apiResponse2(1, 'retrieved', trans('public.success'), [
            'total_devices' => $devices->count(),
            'trusted_devices' => $trustedCount,
            'untrusted_devices' => $untrustedCount,
            'platform_breakdown' => $platformStats,
            'last_registered' => $devices->sortByDesc('first_registered_at')->first(),
        ]);
    }
}
