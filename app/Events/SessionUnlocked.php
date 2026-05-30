<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SessionUnlocked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $live_session_id;
    public $user_id;
    public $booking_id;

    public function __construct(int $live_session_id, int $user_id, int $booking_id)
    {
        $this->live_session_id = $live_session_id;
        $this->user_id = $user_id;
        $this->booking_id = $booking_id;
    }
}