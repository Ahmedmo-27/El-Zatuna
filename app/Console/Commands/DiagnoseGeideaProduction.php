<?php

namespace App\Console\Commands;

use App\Models\PaymentChannel;
use App\Models\Order;
use App\PaymentChannels\ChannelManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiagnoseGeideaProduction extends Command
{
    protected $signature = 'geidea:diagnose {order_id?}';
    protected $description = 'Diagnose Geidea payment issues in production';

    public function handle()
    {
        $this->info('=== Geidea Production Diagnostics ===');
        $this->newLine();

        // 1. Check payment channel
        $this->info('1. Payment Channel Status');
        $channel = PaymentChannel::where('class_name', 'Geidea')->first();
        
        if (!$channel) {
            $this->error('   ❌ Geidea payment channel not found');
            return 1;
        }
        
        $this->info("   ✓ ID: {$channel->id}");
        $this->info("   ✓ Title: {$channel->title}");
        $this->info("   ✓ Status: {$channel->status}");
        $this->info("   ✓ Class: {$channel->class_name}");
        $this->newLine();

        // 2. Check credentials
        $this->info('2. Credentials Check');
        $credentials = $channel->credentials;
        
        if (empty($credentials)) {
            $this->error('   ❌ No credentials configured');
            return 1;
        }
        
        $this->info('   ✓ Public Key: ' . ($credentials['public_key'] ?? 'MISSING'));
        $this->info('   ✓ API Password: ' . (isset($credentials['api_password']) ? '***' . substr($credentials['api_password'], -4) : 'MISSING'));
        $this->info('   ✓ Region: ' . ($credentials['region'] ?? 'MISSING'));
        $this->info('   ✓ Language: ' . ($credentials['language'] ?? 'MISSING'));
        $this->newLine();

        // 3. Check environment configuration
        $this->info('3. Environment Configuration');
        $this->info('   APP_URL: ' . env('APP_URL', 'NOT SET'));
        $this->info('   APP_ENV: ' . env('APP_ENV', 'NOT SET'));
        $this->info('   APP_DEBUG: ' . (env('APP_DEBUG') ? 'true' : 'false'));
        $this->newLine();

        // 4. Check  configuration
        $this->info('4. Geidea Configuration');
        $this->info('   Currency (config): ' . (config('geidea.currency') ?: 'NOT SET'));
        $this->info('   Currency (helper): ' . (currency() ?: 'NOT SET'));
        $this->info('   Callback URL: ' . config('geidea.callback_url'));
        $this->info('   Return URL: ' . config('geidea.return_url'));
        $this->newLine();

        // 5. Test API connectivity with a read-only request
        $this->info('5. API Connectivity Test (read-only)');
        $apiUrls = [
            'EGY' => 'https://api.merchant.geidea.net',
            'KSA' => 'https://api.ksamerchant.geidea.net',
            'UAE' => 'https://api.geidea.ae',
        ];
        
        $region = $credentials['region'] ?? 'EGY';
        $apiUrl = $apiUrls[$region] ?? $apiUrls['EGY'];
        
        $ordersEndpoint = $apiUrl . '/pgw/api/v1/direct/order';
        $this->info("   Testing: {$ordersEndpoint}");
        
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Basic ' . base64_encode("{$credentials['public_key']}:{$credentials['api_password']}"),
                    'Accept' => 'application/json',
                ])
                ->get($ordersEndpoint, [
                    'take' => 1,
                    'skip' => 0,
                ]);

            $this->info("   ✓ Read-only request status: {$response->status()}");

            if ($response->successful()) {
                $body = $response->json();
                $resultCount = is_array($body) ? count($body) : (isset($body['items']) && is_array($body['items']) ? count($body['items']) : null);

                if ($resultCount !== null) {
                    $this->info("   ✓ Response items: {$resultCount}");
                } else {
                    $this->info('   ✓ Response received and decoded successfully');
                }
            } else {
                $this->warn('   ⚠ Response body: ' . $response->body());
            }
        } catch (\Exception $e) {
            $this->error("   ❌ Read-only request failed: {$e->getMessage()}");
        }
        $this->newLine();

        // 6. Test actual payment request if order ID provided
        $orderId = $this->argument('order_id');
        if ($orderId) {
            $this->info("6. Testing Payment Request for Order #{$orderId}");
            $order = Order::find($orderId);
            
            if (!$order) {
                $this->error("   ❌ Order not found");
                return 1;
            }
            
            $this->info("   Order Amount: {$order->total_amount}");
            $this->info("   Order Status: {$order->status}");
            
            try {
                $channelManager = ChannelManager::makeChannel($channel);
                $this->info("   ✓ Channel manager created");
                
                $redirectUrl = $channelManager->paymentRequest($order);
                $this->info("   ✓ Payment request successful");
                $this->info("   ✓ Redirect URL: {$redirectUrl}");
                
                // Check if order was updated
                $order->refresh();
                if ($order->payment_data) {
                    $this->info("   ✓ Order payment_data updated");
                    $paymentData = json_decode($order->payment_data, true);
                    if (isset($paymentData['session_id'])) {
                        $this->info("   ✓ Session ID: {$paymentData['session_id']}");
                    }
                } else {
                    $this->warn("   ⚠ Order payment_data not updated");
                }
                
            } catch (\Exception $e) {
                $this->error("   ❌ Payment request failed:");
                $this->error("   Message: {$e->getMessage()}");
                $this->error("   File: {$e->getFile()}:{$e->getLine()}");
                $this->newLine();
                $this->error("   Stack trace:");
                $this->error($e->getTraceAsString());
                return 1;
            }
        } else {
            $this->comment('6. Skipped (no order_id provided)');
            $this->comment('   Run: php artisan geidea:diagnose <order_id> to test with specific order');
        }
        
        $this->newLine();
        $this->info('=== Diagnostics Complete ===');
        
        return 0;
    }
}
