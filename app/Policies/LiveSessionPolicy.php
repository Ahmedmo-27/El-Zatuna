<?php

namespace App\Policies;

use App\Models\LiveSession;
use App\Models\LiveSessionBooking;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LiveSessionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the live session.
     */
    public function view(?User $user, LiveSession $session)
    {
        if ($user && ($user->isAdmin() || $user->id === $session->creator_id)) {
            return true;
        }

        // Students can only view published sessions matching their university/faculty
        if ($session->status !== 'published') {
            return false;
        }

        if ($user && $session->university_id && $user->university_id !== $session->university_id) {
            return false;
        }

        if ($user && $session->faculty_id && $user->faculty_id !== $session->faculty_id) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can join the live session meeting.
     */
    public function join(User $user, LiveSession $session)
    {
        if ($user->isAdmin() || $user->id === $session->creator_id) {
            return true;
        }

        // Must have a confirmed booking
        return LiveSessionBooking::where('student_id', $user->id)
            ->where('live_session_id', $session->id)
            ->where('status', 'paid')
            ->exists();
    }

    public function update(User $user, LiveSession $session)
    {
        return $user->isAdmin() || $user->id === $session->creator_id;
    }

    public function publish(User $user, LiveSession $session)
    {
        return $this->update($user, $session);
    }

    public function cancel(User $user, LiveSession $session)
    {
        return $this->update($user, $session);
    }

    /**
     * Determine whether the user can manage the live session.
     */
    public function manage(User $user, LiveSession $session)
    {
        return $user->isAdmin() || $user->id === $session->creator_id;
    }
}
