<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use InvalidArgumentException;

class LiveSession extends Model
{
    use SoftDeletes;

    protected $table = 'live_sessions';

    protected $guarded = ['id'];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function teacher()
    {
        return $this->creator();
    }

    public function university()
    {
        return $this->belongsTo(University::class, 'university_id', 'id');
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo(Webinar::class, 'course_id', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(LiveSessionBooking::class, 'live_session_id', 'id');
    }

    public function getAvailableSeatsAttribute()
    {
        if (is_null($this->max_students)) {
            return null; // Unlimited
        }
        $confirmed = $this->confirmed_bookings_count ?? 0;
        $available = $this->max_students - $confirmed;
        
        return $available > 0 ? $available : 0;
    }

    public function canTransitionToStatus(string $newStatus): bool
    {
        $currentStatus = $this->status ?? 'draft';

        if ($newStatus === 'cancelled') {
            return true;
        }

        if ($currentStatus === $newStatus) {
            return true;
        }

        $allowedTransitions = [
            'draft' => ['published'],
            'published' => ['live'],
            'live' => ['completed'],
            'completed' => [],
            'cancelled' => [],
            'archived' => [],
        ];

        return in_array($newStatus, $allowedTransitions[$currentStatus] ?? [], true);
    }

    public function assertCanTransitionToStatus(string $newStatus): void
    {
        if (!$this->canTransitionToStatus($newStatus)) {
            throw new InvalidArgumentException("Invalid live session status transition from {$this->status} to {$newStatus}.");
        }
    }

    public function transitionToStatus(string $newStatus): self
    {
        $this->assertCanTransitionToStatus($newStatus);
        $this->status = $newStatus;

        return $this;
    }
}
