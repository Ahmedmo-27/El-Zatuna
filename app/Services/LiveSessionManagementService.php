<?php

namespace App\Services;

use App\Models\LiveSession;
use App\Models\LiveSessionActivityLog;
use Illuminate\Support\Facades\DB;
use App\Events\LiveSessionPublished;
use App\Events\LiveSessionCancelled;

class LiveSessionManagementService
{
    /**
     * Publish a draft session.
     */
    public function publish(LiveSession $session)
    {
        DB::transaction(function () use ($session) {
            $session->transitionToStatus('published');
            $session->save();

            LiveSessionActivityLog::log(
                $session->id,
                $session->creator_id,
                'session_published',
                'Live session published',
                ['status' => $session->status]
            );

            // Sync with provider if it's API based
            $provider = \App\Services\MeetingProviders\MeetingProviderFactory::make($session->provider);
            $providerData = $provider->syncMeeting($session);

            if (!empty($providerData['join_url'])) {
                $session->update(['provider_url' => $providerData['join_url']]);
            }

            event(new LiveSessionPublished($session));
        });

        return $session;
    }

    /**
     * Cancel a session, handling any necessary refunds.
     */
    public function cancel(LiveSession $session)
    {
        DB::transaction(function () use ($session) {
            $session->transitionToStatus('cancelled');
            $session->save();

            LiveSessionActivityLog::log(
                $session->id,
                $session->creator_id,
                'session_cancelled',
                'Live session cancelled',
                ['status' => $session->status]
            );

            event(new LiveSessionCancelled($session));
            
            // Further logic for batch processing refunds could be triggered by the event
        });

        return $session;
    }
}
