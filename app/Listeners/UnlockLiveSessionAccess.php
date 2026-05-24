<?php

namespace App\Listeners;

use App\Events\LiveSessionBookingCreated;
use App\Events\SessionUnlocked;
use App\Models\LiveSessionActivityLog;
use App\Models\LiveSessionBooking;

class UnlockLiveSessionAccess
{
    public function handle(LiveSessionBookingCreated $event): void
    {
        $booking = LiveSessionBooking::where('id', $event->booking_id)
            ->where('live_session_id', $event->live_session_id)
            ->where('student_id', $event->user_id)
            ->first();

        if (!$booking) {
            return;
        }

        if ($booking->status !== 'paid') {
            $booking->update(['status' => 'paid']);
        }

        LiveSessionActivityLog::log(
            $event->live_session_id,
            $event->user_id,
            'session_unlocked',
            'Live session access unlocked',
            ['booking_id' => $event->booking_id],
            $event->booking_id
        );

        event(new SessionUnlocked(
            $event->live_session_id,
            $event->user_id,
            $event->booking_id
        ));
    }
}