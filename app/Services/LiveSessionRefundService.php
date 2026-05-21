<?php

namespace App\Services;

use App\Models\LiveSessionActivityLog;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LiveSessionRefundService
{
    /**
     * Issue an idempotent refund record for a sale.
     * Does NOT call the payment gateway directly here; it records a refund request
     * that external workers or admin workflows can process. Ensures unique refund_reference.
     */
    public function issueRefundForSale(Sale $sale, ?string $gatewayName = null, ?string $gatewayEventId = null, ?string $refundReference = null)
    {
        $refundReference = $refundReference ?? $this->generateRefundReference($sale, $gatewayEventId);

        // Idempotent insert: if refund_reference already exists, return existing status
        try {
            return DB::transaction(function () use ($sale, $gatewayName, $gatewayEventId, $refundReference) {
                $existing = DB::table('sale_refunds')->where('refund_reference', $refundReference)->first();
                if ($existing) {
                    return $existing;
                }

                $id = DB::table('sale_refunds')->insertGetId([
                    'sale_id' => $sale->id,
                    'refund_reference' => $refundReference,
                    'gateway_name' => $gatewayName,
                    'gateway_event_id' => $gatewayEventId,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if (!empty($sale->live_session_id)) {
                    LiveSessionActivityLog::log(
                        $sale->live_session_id,
                        $sale->buyer_id ?? null,
                        'refund_triggered',
                        'Live session refund request created',
                        [
                            'sale_id' => $sale->id,
                            'refund_reference' => $refundReference,
                            'gateway_name' => $gatewayName,
                        ]
                    );
                }

                return DB::table('sale_refunds')->where('id', $id)->first();
            });
        } catch (\Exception $e) {
            // Unique constraint race: select and return
            return DB::table('sale_refunds')->where('refund_reference', $refundReference)->first();
        }
    }

    private function generateRefundReference(Sale $sale, ?string $gatewayEventId = null): string
    {
        if (!empty($gatewayEventId)) {
            return 'refund_' . $gatewayEventId;
        }

        // Stable fallback hash rather than exposing raw sale id
        return 'refund_' . substr(sha1($sale->id . '|' . ($sale->created_at ?? time())), 0, 32);
    }
}
