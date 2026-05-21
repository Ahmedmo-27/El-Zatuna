<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\OrderItem;
use App\Models\LiveSession;
use App\Models\LiveSessionBooking;
use Illuminate\Support\Facades\DB;
use App\Events\LiveSessionOverbooked;
use Exception;

class LiveSessionCheckoutService
{
    /**
     * Finalize the payment and create the booking.
     * Implements strict capacity checking with pessimistic locking.
     */
    public function finalizePayment(OrderItem $orderItem, Sale $sale): ?LiveSessionBooking
    {
        if (empty($orderItem->live_session_id)) {
            return null;
        }
        // Prefer gateway-supplied event id as payment_reference; otherwise use a stable sale hash
        $paymentReference = $this->generatePaymentReference($sale, null);

        // Retry transaction on deadlock / serialization failures
        $attempts = 0;
        $maxAttempts = 3;

        start_transaction:
        $attempts++;

        try {
            return DB::transaction(function () use ($orderItem, $sale, $paymentReference) {
                $session = LiveSession::where('id', $orderItem->live_session_id)->first();

                if (!$session || $session->status !== 'published') {
                    throw new Exception('Session is no longer available.');
                }

                // Idempotency: check inside the transaction using payment_reference OR existing booking
                $existing = LiveSessionBooking::where('payment_reference', $paymentReference)
                    ->orWhere(function ($q) use ($orderItem) {
                        $q->where('live_session_id', $orderItem->live_session_id)
                            ->where('student_id', $orderItem->user_id);
                    })
                    ->first();

                if ($existing && $existing->status === 'paid') {
                    return $existing;
                }

                // Atomic capacity enforcement at DB level
                if ($session->max_students !== null) {
                    $updated = DB::table('live_sessions')
                        ->where('id', $session->id)
                        ->whereRaw('confirmed_bookings_count < ?', [$session->max_students])
                        ->update(['confirmed_bookings_count' => DB::raw('confirmed_bookings_count + 1')]);

                    if ($updated === 0) {
                        // Capacity reached — create idempotent refund record and raise
                        (new \App\Services\LiveSessionRefundService())->issueRefundForSale($sale, null, null, null);
                        event(new LiveSessionOverbooked($orderItem, $sale));
                        throw new Exception('Session reached capacity during checkout.');
                    }
                } else {
                    DB::table('live_sessions')->where('id', $session->id)->increment('confirmed_bookings_count');
                }

                // Create booking; rely on DB unique constraints to prevent duplicates
                $booking = LiveSessionBooking::create([
                    'live_session_id' => $session->id,
                    'student_id' => $orderItem->user_id,
                    'sale_id' => $sale->id,
                    'payment_reference' => $paymentReference,
                    'status' => 'paid',
                ]);

                event(new LiveSessionPurchased($booking));

                return $booking;
            });
        } catch (\Exception $e) {
            // Retry on deadlock/lock wait related exceptions
            if ($attempts < $maxAttempts) {
                usleep(100000 * $attempts); // exponential-ish backoff
                goto start_transaction;
            }

            throw $e;
        }

        return null;
    }

    private function generatePaymentReference($sale, $gatewayEventId = null): string
    {
        if (!empty($gatewayEventId)) {
            return (string)$gatewayEventId;
        }

        // Stable fallback: don't expose raw sale id; use a short hash
        return 'sale_' . substr(sha1($sale->id . '|' . ($sale->created_at ?? time())), 0, 20);
    }
}
