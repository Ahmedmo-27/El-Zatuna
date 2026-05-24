<?php

namespace App\Services\MeetingProviders;

use App\Models\LiveSession;
use App\User;

class ManualLinkProvider implements MeetingProviderContract
{
    /**
     * Return the manually entered join URL from the database.
     */
    public function getJoinUrl(LiveSession $session, User $student): ?string
    {
        return $session->provider_url;
    }

    /**
     * Manual providers generally share the same URL for host and participant.
     */
    public function getHostUrl(LiveSession $session): ?string
    {
        return $session->provider_url;
    }

    /**
     * No API syncing needed for manual links.
     */
    public function syncMeeting(LiveSession $session): array
    {
        return [];
    }
}
