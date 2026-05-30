<?php

namespace App\Jobs;

use App\Models\Sale;
use App\Models\PaymentChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\PaymentChannels\ChannelManager;

class ExecuteSaleRefund implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $refundId;

    public function __construct(int $refundId)
    {
        $this->refundId = $refundId;
    }

    public function handle()
    {
        $refund = DB::table('sale_refunds')->where('id', $this->refundId)->first();
        if (!$refund || $refund->status !== 'pending') {
            return;
        }

        try {
            DB::beginTransaction();

            // reload inside tx
            $refund = DB::table('sale_refunds')->where('id', $this->refundId)->lockForUpdate()->first();
            if (!$refund || $refund->status !== 'pending') {
                DB::commit();
                return;
            }

            // increment attempt count
            $attempts = ($refund->attempt_count ?? 0) + 1;
            DB::table('sale_refunds')->where('id', $this->refundId)->update(['attempt_count' => $attempts, 'updated_at' => now()]);

            // load sale
            $sale = Sale::find($refund->sale_id);

            // Resolve payment channel
            $paymentChannel = null;
            if (!empty($refund->gateway_name)) {
                $paymentChannel = PaymentChannel::where('class_name', $refund->gateway_name)->first();
            }

            if (!$paymentChannel && $sale) {
                // try to infer from sale/order
                $paymentChannel = PaymentChannel::where('id', $sale->payment_channel_id ?? null)->first();
            }

            if (!$paymentChannel) {
                DB::table('sale_refunds')->where('id', $this->refundId)
                    ->update(['status' => 'failed', 'last_error_message' => 'No payment channel found', 'updated_at' => now()]);
                DB::commit();
                return;
            }

            $channel = ChannelManager::makeChannel($paymentChannel);

            // Determine gateway order identifier — prefer gateway_event_id if present
            $gatewayOrderId = $refund->gateway_event_id ?? ($sale->order_id ?? $sale->id ?? null);

            $response = $channel->refund((string)$gatewayOrderId, null);

            if ($response !== null) {
                // success
                DB::table('sale_refunds')->where('id', $this->refundId)->update([
                    'status' => 'processed',
                    'last_error_message' => null,
                    'processed_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $error = 'Refund failed: gateway returned null';
                DB::table('sale_refunds')->where('id', $this->refundId)->update([
                    'status' => $attempts >= 5 ? 'failed' : 'pending',
                    'last_error_message' => $error,
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ExecuteSaleRefund exception', ['id' => $this->refundId, 'message' => $e->getMessage()]);
            DB::table('sale_refunds')->where('id', $this->refundId)->update([
                'status' => 'pending',
                'last_error_message' => $e->getMessage(),
                'updated_at' => now(),
            ]);
            // Let the queue retry according to worker settings
            throw $e;
        }
    }
}
