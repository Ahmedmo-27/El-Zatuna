<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentSucceeded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sale_id;
    public $user_id;
    public $items;
    public $payment_reference;

    public function __construct(int $sale_id, int $user_id, array $items, ?string $payment_reference = null)
    {
        $this->sale_id = $sale_id;
        $this->user_id = $user_id;
        $this->items = $items;
        $this->payment_reference = $payment_reference;
    }
}