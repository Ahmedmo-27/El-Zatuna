<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Api\Controller;
use App\Models\DeleteAccountRequest;
use Illuminate\Http\Request;

class DeleteAccountRequestController extends Controller
{
    /**
     * Request account deletion
     * Consolidated endpoint for account deletion requests
     */
    public function store(Request $request)
    {
        validateParam($request->all(), [
            'reason' => 'nullable|string|max:500',
            'password' => 'required|string'
        ]);

        $user = apiAuth();

        // Verify password for security
        if (!\Hash::check($request->input('password'), $user->password)) {
            return apiResponse2(0, 'invalid_password', trans('auth.password_invalid'), null, null, 401);
        }

        $deletionRequest = DeleteAccountRequest::updateOrCreate([
            'user_id' => $user->id,
        ], [
            'reason' => $request->input('reason'),
            'created_at' => time()
        ]);

        // Calculate scheduled deletion date (e.g., 30 days from now)
        $scheduledDeletionDate = date('Y-m-d\TH:i:s\Z', strtotime('+30 days'));

        return apiResponse2(1, 'deletion_requested', trans('update.delete_account_request_stored_msg'), [
            'request_id' => $deletionRequest->id,
            'scheduled_deletion_date' => $scheduledDeletionDate
        ]);
    }
}
