<?php

namespace App\Services;

use App\Models\LiveSession;
use App\Models\LiveSessionActivityLog;
use App\Models\LiveSessionBooking;
use App\Services\MeetingProviders\MeetingProviderFactory;
use App\User;
use Illuminate\Support\Facades\Gate;
use RuntimeException;

class LiveSessionJoinService
{
    public function canJoin(User $user, LiveSession $session): bool
    {
        if (!$user || !$session) {
            return false;
        }

        if ($session->status === 'cancelled' || $session->status === 'completed' || $session->status === 'archived') {
            return false;
        }

        if (!$this->isPrivilegedUser($user, $session)) {
            if (empty($session->start_at) || empty($session->end_at)) {
                return false;
            }

            $joinWindowOpensAt = $session->start_at->copy()->subMinutes(15);
            if (now()->lt($joinWindowOpensAt) || now()->gt($session->end_at)) {
                return false;
            }

            $booking = LiveSessionBooking::where('live_session_id', $session->id)
                ->where('student_id', $user->id)
                ->where('status', 'paid')
                ->first();

            if (!$booking) {
                return false;
            }
        }

        return Gate::forUser($user)->allows('join', $session);
    }

    public function getProviderUrl(LiveSession $session, User $user): string
    {
        if (!$this->canJoin($user, $session)) {
            LiveSessionActivityLog::log(
                $session->id,
                $user->id ?? null,
                'join_attempt_denied',
                'Join URL request denied',
                ['status' => $session->status]
            );

            throw new RuntimeException('You are not allowed to join this session.');
        }

        LiveSessionActivityLog::log(
            $session->id,
            $user->id ?? null,
            'join_access_attempt',
            'Join URL requested',
            ['provider' => $session->provider]
        );

        $provider = MeetingProviderFactory::make((string) $session->provider);
        $joinUrl = $provider->getJoinUrl($session, $user);

        if (empty($joinUrl)) {
            throw new RuntimeException('Join URL is unavailable for this session.');
        }

        return $joinUrl;
    }

    private function isPrivilegedUser(User $user, LiveSession $session): bool
    {
        return $user->isAdmin() || $user->id === $session->creator_id;
    }
}