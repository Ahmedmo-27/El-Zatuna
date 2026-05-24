<?php

namespace App\Events;

use App\Models\LiveSessionBooking;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LiveSessionPurchased
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(LiveSessionBooking $booking)
    {
        $this->booking = $booking;
    }
}
