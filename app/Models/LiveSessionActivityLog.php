<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class LiveSessionActivityLog extends Model
{
    protected $table = 'live_session_activity_logs';

    protected $guarded = ['id'];

    protected $casts = [
        'payload' => 'array',
    ];

    public function liveSession()
    {
        return $this->belongsTo(LiveSession::class, 'live_session_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function booking()
    {
        return $this->belongsTo(LiveSessionBooking::class, 'booking_id', 'id');
    }

    public static function log(int $liveSessionId, ?int $userId, string $action, ?string $description = null, $payload = null, ?int $bookingId = null): self
    {
        return self::create([
            'live_session_id' => $liveSessionId,
            'user_id' => $userId,
            'booking_id' => $bookingId,
            'action' => $action,
            'description' => $description,
            'payload' => $payload,
        ]);
    }
}
