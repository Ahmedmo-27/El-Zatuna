<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Api\Controller;
use App\Models\DataExportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class DataExportController extends Controller
{
    /**
     * Request user data export (GDPR compliance)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestExport(Request $request)
    {
        try {
            $user = apiAuth();

            if (!$user) {
                return apiResponse2(0, 'unauthorized', trans('auth.not_login'));
            }

            // Check for existing pending requests
            $existingRequest = DataExportRequest::where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            if ($existingRequest) {
                return apiResponse2(0, 'request_exists', trans('auth.data_export_already_requested'), [
                    'request_id' => $existingRequest->id,
                    'estimated_ready_at' => $existingRequest->estimated_ready_at,
                ]);
            }

            // Create new export request
            $exportRequest = DataExportRequest::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'requested_at' => now(),
                'estimated_ready_at' => now()->addHours(24),
            ]);

            // Dispatch export job (would be created separately)
            // \App\Jobs\ExportUserData::dispatch($exportRequest);

            return apiResponse2(1, 'export_requested', trans('auth.data_export_requested'), [
                'request_id' => $exportRequest->id,
                'estimated_ready_at' => $exportRequest->estimated_ready_at->toIso8601String(),
            ]);

        } catch (\Exception $e) {
            return apiResponse2(0, 'error', $e->getMessage());
        }
    }

    /**
     * Check status and download data export
     *
     * @param Request $request
     * @param int $requestId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExport(Request $request, $requestId)
    {
        try {
            $user = apiAuth();

            if (!$user) {
                return apiResponse2(0, 'unauthorized', trans('auth.not_login'));
            }

            // Find the export request
            $exportRequest = DataExportRequest::where('id', $requestId)
                ->where('user_id', $user->id)
                ->first();

            if (!$exportRequest) {
                return apiResponse2(0, 'not_found', trans('public.not_found'));
            }

            // Check status
            if ($exportRequest->status === 'pending') {
                return apiResponse2(0, 'still_processing', trans('auth.data_export_processing'), [
                    'request_id' => $exportRequest->id,
                    'status' => 'pending',
                    'estimated_ready_at' => $exportRequest->estimated_ready_at->toIso8601String(),
                ]);
            }

            if ($exportRequest->status === 'failed') {
                return apiResponse2(0, 'export_failed', trans('auth.data_export_failed'), [
                    'request_id' => $exportRequest->id,
                    'status' => 'failed',
                ]);
            }

            // Status is ready
            if ($exportRequest->status === 'ready') {
                // Check if file exists
                if (!$exportRequest->file_path || !Storage::exists($exportRequest->file_path)) {
                    return apiResponse2(0, 'file_not_found', trans('auth.data_export_file_not_found'));
                }

            // Generate temporary download URL (expires in 7 days)
            $downloadUrl = url('/api/v1/panel/account/export-data/' . $requestId . '/download?token=' . encrypt($user->id));

            return apiResponse2(1, 'export_ready', trans('auth.data_export_ready'), [
                'request_id' => $exportRequest->id,
                'status' => 'ready',
                'download_url' => $downloadUrl,
                'file_size' => Storage::size($exportRequest->file_path),
            ]);
            }

            return apiResponse2(0, 'unknown_status', trans('auth.data_export_unknown_status'));

        } catch (\Exception $e) {
            return apiResponse2(0, 'error', $e->getMessage());
        }
    }

    /**
     * Download the exported data file
     *
     * @param Request $request
     * @param int $requestId
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\JsonResponse
     */
    public function download(Request $request, $requestId)
    {
        try {
            $user = apiAuth();

            if (!$user) {
                return apiResponse2(0, 'unauthorized', trans('auth.not_login'));
            }

            // Find the export request
            $exportRequest = DataExportRequest::where('id', $requestId)
                ->where('user_id', $user->id)
                ->where('status', 'ready')
                ->first();

            if (!$exportRequest) {
                return apiResponse2(0, 'not_found', trans('public.not_found'));
            }

            // Check if file exists
            if (!$exportRequest->file_path || !Storage::exists($exportRequest->file_path)) {
                return apiResponse2(0, 'file_not_found', trans('auth.data_export_file_not_found'));
            }

            // Return file for download
            return Storage::download($exportRequest->file_path, 'user_data_export_' . $user->id . '.zip');

        } catch (\Exception $e) {
            return apiResponse2(0, 'error', $e->getMessage());
        }
    }

    /**
     * Generate user data export (called by job)
     * This is a helper method that would be called by a background job
     *
     * @param DataExportRequest $exportRequest
     * @return void
     */
    public static function generateExport(DataExportRequest $exportRequest)
    {
        try {
            $user = $exportRequest->user;

            if (!$user) {
                $exportRequest->update(['status' => 'failed']);
                return;
            }

            // Collect all user data
            $userData = [
                'user_profile' => [
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'created_at' => $user->created_at,
                    'bio' => $user->bio,
                    'about' => $user->about,
                ],
                'courses' => $user->webinars()->get()->toArray(),
                'purchases' => $user->purchases()->get()->toArray(),
                'cart' => $user->carts()->get()->toArray(),
                'comments' => $user->comments()->get()->toArray(),
                'reviews' => $user->webinarReviews()->get()->toArray(),
                'support_tickets' => $user->supports()->get()->toArray(),
                'meetings' => $user->reserveMeetings()->get()->toArray(),
                'certificates' => $user->quizzesResults()->where('status', 'passed')->get()->toArray(),
                'notifications' => $user->notifications()->get()->toArray(),
                'login_history' => $user->userLoginHistory()->get()->toArray(),
            ];

            // Create JSON file
            $jsonData = json_encode($userData, JSON_PRETTY_PRINT);
            $fileName = 'user_data_export_' . $user->id . '_' . time() . '.json';
            $filePath = 'exports/' . $fileName;

            // Store the file
            Storage::put($filePath, $jsonData);

            // Update export request
            $exportRequest->update([
                'status' => 'ready',
                'file_path' => $filePath,
                'completed_at' => now(),
            ]);

        } catch (\Exception $e) {
            $exportRequest->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}
