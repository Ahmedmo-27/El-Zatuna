<?php

namespace App\Listeners;

use App\Events\LiveSessionBookingCreated;
use App\Events\PaymentSucceeded;
use App\Models\LiveSessionActivityLog;
use App\Models\LiveSessionBooking;
use App\Models\OrderItem;
use App\Models\Sale;
use App\Services\LiveSessionCheckoutService;

class CreateLiveSessionBooking
{
    public function __construct(private LiveSessionCheckoutService $checkoutService)
    {
    }

    public function handle(PaymentSucceeded $event): void
    {
        $sale = Sale::find($event->sale_id);
        if (!$sale) {
            return;
        }

        foreach ($event->items as $itemData) {
            $liveSessionId = $itemData['live_session_id'] ?? null;
            if (empty($liveSessionId)) {
                continue;
            }

            $orderItemId = $itemData['id'] ?? null;
            $orderItem = $orderItemId ? OrderItem::find($orderItemId) : null;

            if (!$orderItem) {
                $orderItem = OrderItem::where('order_id', $sale->order_id)
                    ->where('live_session_id', $liveSessionId)
                    ->where('user_id', $event->user_id)
                    ->first();
            }

            if (!$orderItem) {
                continue;
            }

            LiveSessionActivityLog::log(
                (int) $liveSessionId,
                (int) $event->user_id,
                'payment_succeeded',
                'Payment succeeded for live session purchase',
                [
                    'sale_id' => $event->sale_id,
                    'payment_reference' => $event->payment_reference,
                ]
            );

            $existingBooking = LiveSessionBooking::where([
                'student_id' => $event->user_id,
                'live_session_id' => $liveSessionId,
                'sale_id' => $event->sale_id,
            ])->first();

            if ($existingBooking) {
                LiveSessionActivityLog::log(
                    (int) $liveSessionId,
                    (int) $event->user_id,
                    'booking_created',
                    'Live session booking already exists for sale',
                    [
                        'sale_id' => $event->sale_id,
                        'booking_id' => $existingBooking->id,
                    ],
                    $existingBooking->id
                );

                event(new LiveSessionBookingCreated(
                    (int) $liveSessionId,
                    (int) $event->user_id,
                    (int) $existingBooking->id
                ));

                continue;
            }

            $booking = $this->checkoutService->finalizePayment($orderItem, $sale);

            if (!$booking) {
                continue;
            }

            LiveSessionActivityLog::log(
                (int) $liveSessionId,
                (int) $event->user_id,
                'booking_created',
                'Live session booking created after payment confirmation',
                [
                    'sale_id' => $event->sale_id,
                    'booking_id' => $booking->id,
                ],
                $booking->id
            );

            event(new LiveSessionBookingCreated(
                (int) $liveSessionId,
                (int) $event->user_id,
                (int) $booking->id
            ));
        }
    }
}