<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\PaymentChannel;
use App\PaymentChannels\ChannelManager;

/**
 * Geidea Return URL Handler
 * 
 * This route handles the return URL when Geidea redirects the user back to our site
 * after payment. It verifies the payment status and completes the order.
 * 
 * This is a FALLBACK in case the callback URL is not working
 */
Route::get('/geidea/return', function(\Illuminate\Http\Request $request) {
    Log::info('Geidea Return URL accessed', [
        'all_params' => $request->all(),
        'query_params' => $request->query(),
        'url' => $request->fullUrl(),
        'ip' => $request->ip(),
        'user_id' => auth()->id(),
        'is_authenticated' => auth()->check(),
    ]);
    
    $orderId = $request->query('orderId');
    $sessionId = $request->query('sessionId');
    $responseCode = $request->query('responseCode');
    $responseMessage = $request->query('responseMessage');
    
    Log::info('Geidea Return URL parsed params', [
        'orderId' => $orderId,
        'sessionId' => $sessionId,
        'responseCode' => $responseCode,
        'responseMessage' => $responseMessage,
    ]);
    
    if (empty($orderId)) {
        Log::warning('Geidea return URL missing orderId');
        return redirect('/')->with(['toast' => [
            'title' => 'Payment Error',
            'msg' => 'Missing order information',
            'status' => 'error'
        ]]);
    }
    
    // Find the order by Geidea session ID in payment_data
    // Try multiple approaches to find the order
    $order = null;
    
    // Approach 1: Find by session ID in JSON
    if ($sessionId) {
        $order = Order::where('status', 'pending')
            ->where('payment_method', 'payment_channel')
            ->where('payment_data', 'like', '%"session_id":"' . $sessionId . '"%')
            ->orderBy('created_at', 'desc')
            ->first();
    }
    
    // Approach 2: If not found, find the most recent pending order for this user
    // (assuming user is still logged in)
    if (!$order && auth()->check()) {
        $order = Order::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->where('payment_method', 'payment_channel')
            ->orderBy('created_at', 'desc')
            ->first();
    }
    
    if (!$order) {
        Log::warning('Geidea order not found', [
            'orderId' => $orderId,
            'sessionId' => $sessionId,
            'auth_check' => auth()->check(),
            'user_id' => auth()->id(),
        ]);
        return redirect('/')->with(['toast' => [
            'title' => 'Payment Error',
            'msg' => 'Order not found',
            'status' => 'error'
        ]]);
    }
    
    Log::info('Geidea order found, verifying payment', [
        'order_id' => $order->id,
        'geidea_order_id' => $orderId,
        'response_code' => $responseCode,
    ]);
    
    // Get Geidea payment channel
    $paymentChannel = PaymentChannel::where('class_name', 'Geidea')
        ->where('status', 'active')
        ->first();
    
    if (!$paymentChannel) {
        Log::error('Geidea payment channel not found or not active');
        return redirect('/')->with(['toast' => [
            'title' => 'Payment Error',
            'msg' => 'Payment gateway not available',
            'status' => 'error'
        ]]);
    }
    
    try {
        // Create a mock request with Geidea callback data
        $mockRequest = new \Illuminate\Http\Request([
            'orderId' => $orderId,
            'sessionId' => $sessionId,
            'responseCode' => $responseCode,
            'responseMessage' => $responseMessage,
        ]);
        
        // Use the channel's verify method
        $channelManager = ChannelManager::makeChannel($paymentChannel);
        $verifiedOrder = $channelManager->verify($mockRequest);
        
        if ($verifiedOrder && $verifiedOrder->status === Order::$paying) {
            // Complete the order
            app(\App\Http\Controllers\Web\PaymentController::class)->paymentOrderAfterVerify($verifiedOrder);
            
            Log::info('Geidea payment verified and completed via return URL', [
                'order_id' => $verifiedOrder->id,
                'status' => $verifiedOrder->status,
            ]);
            
            return redirect("/payments/status?t={$verifiedOrder->id}");
        } else {
            Log::warning('Geidea payment verification failed', [
                'order_id' => $order->id,
                'geidea_order_id' => $orderId,
            ]);
            
            return redirect('/')->with(['toast' => [
                'title' => 'Payment Failed',
                'msg' => 'Payment verification failed. Please contact support.',
                'status' => 'error'
            ]]);
        }
        
    } catch (\Exception $e) {
        Log::error('Geidea return URL verification exception', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'order_id' => $order->id ?? 'unknown',
        ]);
        
        return redirect('/')->with(['toast' => [
            'title' => 'Payment Error',
            'msg' => 'An error occurred while processing your payment',
            'status' => 'error'
        ]]);
    }
})->name('geidea.return');
