<?php

namespace App\Events;

use App\Models\OrderItem;
use App\Models\Sale;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LiveSessionOverbooked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $orderItem;
    public $sale;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(OrderItem $orderItem, Sale $sale)
    {
        $this->orderItem = $orderItem;
        $this->sale = $sale;
    }
}
