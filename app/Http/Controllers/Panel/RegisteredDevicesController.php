<?php

namespace App\Http\Controllers\Panel;

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
     * Display registered devices page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();
        
        $devices = UserRegisteredDevice::where('user_id', $user->id)
            ->orderBy('last_used_at', 'desc')
            ->get();

        $data = [
            'pageTitle' => trans('panel.registered_devices'),
            'devices' => $devices,
        ];

        return view('web.default.panel.settings.registered_devices', $data);
    }

    /**
     * Update device trust status
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTrust(Request $request, $id)
    {
        $user = auth()->user();

        $device = UserRegisteredDevice::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$device) {
            $toastData = [
                'title' => trans('public.request_failed'),
                'msg' => trans('public.device_not_found'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }

        $isTrusted = $request->input('is_trusted') === '1' || $request->input('is_trusted') === true;

        $device->update([
            'is_trusted' => $isTrusted
        ]);

        $action = $isTrusted ? 'trusted' : 'untrusted';

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('public.device_' . $action . '_successfully'),
            'status' => 'success'
        ];

        return back()->with(['toast' => $toastData]);
    }

    /**
     * Remove a registered device
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $user = auth()->user();

        $device = UserRegisteredDevice::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$device) {
            $toastData = [
                'title' => trans('public.request_failed'),
                'msg' => trans('public.device_not_found'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }

        // Check if this is the last device
        $deviceCount = UserRegisteredDevice::where('user_id', $user->id)->count();
        
        if ($deviceCount <= 1) {
            $toastData = [
                'title' => trans('public.request_failed'),
                'msg' => trans('public.cannot_delete_last_registered_device'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }

        // Delete device
        $device->delete();

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('public.device_removed_successfully'),
            'status' => 'success'
        ];

        return back()->with(['toast' => $toastData]);
    }

    /**
     * Get statistics about registered devices (AJAX endpoint)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats()
    {
        $user = auth()->user();

        $devices = UserRegisteredDevice::where('user_id', $user->id)->get();
        
        $trustedCount = $devices->where('is_trusted', true)->count();
        $untrustedCount = $devices->where('is_trusted', false)->count();

        $platformStats = [
            'mobile' => $devices->where('platform', 'mobile')->count(),
            'tablet' => $devices->where('platform', 'tablet')->count(),
            'desktop' => $devices->where('platform', 'desktop')->count(),
        ];

        return response()->json([
            'total_devices' => $devices->count(),
            'trusted_devices' => $trustedCount,
            'untrusted_devices' => $untrustedCount,
            'platform_breakdown' => $platformStats,
            'last_registered' => $devices->sortByDesc('first_registered_at')->first(),
        ]);
    }
}
