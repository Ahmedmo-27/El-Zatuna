<?php

namespace App\Services\MeetingProviders;

use App\Models\LiveSession;
use App\User;

interface MeetingProviderContract
{
    /**
     * Get the URL for a student to join the session.
     */
    public function getJoinUrl(LiveSession $session, User $student): ?string;

    /**
     * Get the URL for the host/teacher to start the session.
     */
    public function getHostUrl(LiveSession $session): ?string;

    /**
     * Sync the meeting with the provider's API (create/update the meeting remotely).
     * Returns an array of provider-specific data (e.g., meeting ID, URLs).
     */
    public function syncMeeting(LiveSession $session): array;
}
