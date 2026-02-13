<?php

namespace App\PaymentChannels\Drivers\Geidea;

use App\Models\Order;
use App\Models\PaymentChannel;
use App\PaymentChannels\BasePaymentChannel;
use App\PaymentChannels\IChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Geidea Payment Gateway Channel
 * 
 * Integrates with Geidea Payment Gateway for processing payments
 * Supports regions: KSA (Saudi Arabia), EGY (Egypt), UAE
 * 
 * @see https://docs.geidea.net/docs
 */
class Channel extends BasePaymentChannel implements IChannel
{
    protected $currency;
    protected $test_mode;
    protected $public_key;
    protected $api_password;
    protected $region;
    protected $language;
    protected $payment_operation;
    protected $card_on_file;

    protected array $credentialItems = [
        'public_key',
        'api_password',
        'region' => ['KSA', 'EGY', 'UAE'],
        'language' => ['en', 'ar'],
    ];

    /**
     * Available API URLs for different regions
     */
    private const API_URLS = [
        'KSA' => 'https://api.ksamerchant.geidea.net',
        'EGY' => 'https://api.merchant.geidea.net',
        'UAE' => 'https://api.geidea.ae',
    ];

    /**
     * Available HPP (Hosted Payment Page) URLs for different regions
     */
    private const HPP_URLS = [
        'KSA' => 'https://www.ksamerchant.geidea.net',
        'EGY' => 'https://www.merchant.geidea.net',
        'UAE' => 'https://payments.geidea.ae',
    ];

    /**
     * Supported currencies by Geidea
     */
    private const SUPPORTED_CURRENCIES = [
        'SAR', 'EGP', 'AED', 'QAR', 'OMR', 'BHD', 'KWD', 'USD', 'GBP', 'EUR'
    ];

    public function __construct(PaymentChannel $paymentChannel)
    {
        $this->setCredentialItems($paymentChannel);
        
        // Set currency - prefer config, fallback to system currency
        $this->currency = config('geidea.currency') ?: currency();
        
        // Ensure currency is set
        if (empty($this->currency)) {
            $this->currency = 'EGP'; // Default to EGP for Egypt
        }

        // Set defaults if not provided
        if (empty($this->region)) {
            $this->region = "EGY";
        }

        if (empty($this->language)) {
            $this->language = "en";
        }

        $this->payment_operation = config('geidea.payment_operation', 'Pay');
        
        // Ensure card_on_file is a strict boolean (Geidea API requires boolean, not string)
        $cardOnFileConfig = config('geidea.card_on_file', false);
        $this->card_on_file = (bool) filter_var($cardOnFileConfig, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
    }

    /**
     * Get the API base URL for the configured region
     */
    private function getApiUrl(): string
    {
        return self::API_URLS[$this->region] ?? self::API_URLS['KSA'];
    }

    /**
     * Get the HPP base URL for the configured region
     */
    private function getHppUrl(): string
    {
        return self::HPP_URLS[$this->region] ?? self::HPP_URLS['KSA'];
    }

    /**
     * Generate signature for API security
     * Signature = Base64(HMAC-SHA256(concatenated_data, api_password))
     * 
     * @param float $amount
     * @param string $currency
     * @param string $merchantReferenceId
     * @param string $timestamp
     * @return string
     */
    private function generateSignature(float $amount, string $currency, string $merchantReferenceId, string $timestamp): string
    {
        // Format amount with 2 decimals
        $amountStr = number_format($amount, 2, '.', '');
        
        // Concatenate: {MerchantPublicKey}{Amount}{Currency}{MerchantReferenceId}{Timestamp}
        $data = "{$this->public_key}{$amountStr}{$currency}{$merchantReferenceId}{$timestamp}";
        
        // Generate HMAC-SHA256 hash
        $hash = hash_hmac('sha256', $data, $this->api_password, true);
        
        // Return Base64 encoded hash
        return base64_encode($hash);
    }

    /**
     * Get Basic Authentication header
     * 
     * @return string
     */
    private function getAuthHeader(): string
    {
        return 'Basic ' . base64_encode("{$this->public_key}:{$this->api_password}");
    }

    /**
     * Create a payment session with Geidea
     * 
     * @param Order $order
     * @return array|null
     */
    private function createSession(Order $order): ?array
    {
        $generalSettings = getGeneralSettings();
        $user = $order->user;
        $price = $this->makeAmountByCurrency($order->total_amount, $this->currency);
        
        // Generate timestamp
        $timestamp = date('Y/m/d H:i:s');
        
        // Generate merchant reference ID
        $merchantReferenceId = 'ORDER-' . $order->id . '-' . time();
        
        // Generate signature
        $signature = $this->generateSignature($price, $this->currency, $merchantReferenceId, $timestamp);
        
        // Prepare session data
        $sessionData = [
            'amount' => $price,
            'currency' => $this->currency,
            'timestamp' => $timestamp,
            'merchantReferenceId' => $merchantReferenceId,
            'signature' => $signature,
            'callbackUrl' => $this->makeCallbackUrl(),
            'returnUrl' => $this->makeReturnUrl(),
            'language' => $this->language,
            'paymentOperation' => $this->payment_operation,
            'customer' => [
                'email' => $user->email,
                'phoneNumber' => $user->mobile ?? '+20000000000', // Provide default if missing
                'firstName' => $user->first_name ?? $user->full_name,
                'lastName' => $user->last_name ?? '',
            ],
        ];
        
        // Only include cardOnFile if it's explicitly true (omit when false to avoid validation errors)
        if ($this->card_on_file === true) {
            $sessionData['cardOnFile'] = true;
        }

        try {
            // Log request for debugging
            Log::info('Geidea session creation request', [
                'order_id' => $order->id,
                'amount' => $sessionData['amount'],
                'currency' => $sessionData['currency'],
                'cardOnFile' => $sessionData['cardOnFile'],
                'cardOnFile_type' => gettype($sessionData['cardOnFile']),
                'api_url' => $this->getApiUrl(),
            ]);

            // Make API request to create session
            $response = Http::withOptions(['verify' => false])
                ->withHeaders([
                    'Authorization' => $this->getAuthHeader(),
                    'Content-Type' => 'application/json',
                ])->post($this->getApiUrl() . '/payment-intent/api/v2/direct/session', $sessionData);

            $responseData = $response->json();

            // Check for successful response
            if ($response->successful() && 
                isset($responseData['responseCode']) && 
                $responseData['responseCode'] === '000') {
                
                return $responseData;
            }

            // Log error with more details
            Log::error('Geidea session creation failed', [
                'response' => $responseData,
                'order_id' => $order->id,
                'status_code' => $response->status(),
                'error_code' => $responseData['detailedResponseCode'] ?? 'unknown',
                'error_message' => $responseData['detailedResponseMessage'] ?? 'unknown',
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('Geidea session creation exception', [
                'message' => $e->getMessage(),
                'order_id' => $order->id,
            ]);
            
            return null;
        }
    }

    /**
     * Handle payment request
     * 
     * @param Order $order
     * @return string|null URL to redirect customer to Geidea checkout page
     * @throws \Exception
     */
    public function paymentRequest(Order $order)
    {
        $sessionResponse = $this->createSession($order);

        if (!$sessionResponse || !isset($sessionResponse['session']['id'])) {
            throw new \Exception('Unable to process payment. Please contact support or try a different payment method.');
        }

        $sessionId = $sessionResponse['session']['id'];

        // Store session data in order for later verification
        $order->update([
            'payment_data' => json_encode([
                'session_id' => $sessionId,
                'session_response' => $sessionResponse,
                'gateway' => 'Geidea',
            ]),
        ]);

        // Return checkout URL for redirect
        return $this->getHppUrl() . '/hpp/checkout/?' . $sessionId;
    }

    /**
     * Make callback URL for payment notifications
     * 
     * @return string
     */
    private function makeCallbackUrl(): string
    {
        // Use configured callback URL or generate route
        $configuredUrl = config('geidea.callback_url');
        
        if ($configuredUrl) {
            return $configuredUrl;
        }
        
        return route('payment_verify', [
            'gateway' => 'Geidea'
        ]);
    }

    /**
     * Make return URL for redirecting customers after payment
     * 
     * @return string
     */
    private function makeReturnUrl(): string
    {
        return config('geidea.return_url') ?: url('/');
    }

    /**
     * Verify payment callback from Geidea
     * 
     * @param Request $request
     * @return Order|null
     */
    public function verify(Request $request)
    {
        $data = $request->all();
        $order = null;

        Log::info('Geidea payment callback received', [
            'data' => $data,
            'url' => $request->fullUrl(),
            'method' => $request->method(),
        ]);

        // Geidea sends the complete order data in the callback
        // Check both formats: direct orderId parameter OR nested in order object
        $geideaOrderId = null;
        $geideaOrder = null;
        
        if (!empty($data['orderId'])) {
            // Format 1: Direct orderId parameter (used in return URL)
            $geideaOrderId = $data['orderId'];
        } elseif (!empty($data['order']['orderId'])) {
            // Format 2: Nested in order object (used in callback POST)
            $geideaOrder = $data['order'];
            $geideaOrderId = $geideaOrder['orderId'];
        }

        if (!empty($geideaOrderId)) {
            try {
                // If we don't have the full order details, fetch them from API
                if (empty($geideaOrder)) {
                    Log::info('Geidea fetching order details from API', ['orderId' => $geideaOrderId]);
                    
                    $orderDetails = $this->fetchOrderDetails($geideaOrderId);
                    
                    if ($orderDetails && isset($orderDetails['order'])) {
                        $geideaOrder = $orderDetails['order'];
                    }
                } else {
                    Log::info('Geidea using order details from callback', [
                        'orderId' => $geideaOrderId,
                        'status' => $geideaOrder['status'] ?? 'unknown',
                    ]);
                }

                if ($geideaOrder) {
                    
                    Log::info('Geidea order details fetched', [
                        'status' => $geideaOrder['status'] ?? 'unknown',
                        'merchantReferenceId' => $geideaOrder['merchantReferenceId'] ?? 'unknown',
                    ]);
                    
                    // Find our order using merchant reference ID or order ID from payment_data
                    $order = $this->findOrderByGeideaData($geideaOrder);

                    if ($order) {
                        Log::info('Geidea order found', [
                            'order_id' => $order->id,
                            'current_status' => $order->status,
                            'geidea_status' => $geideaOrder['status'] ?? 'unknown',
                        ]);
                        
                        Auth::loginUsingId($order->user_id);

                        // Determine order status based on Geidea response
                        $orderStatus = Order::$fail;

                        // Check if payment was successful
                        if (isset($geideaOrder['status']) && 
                            in_array($geideaOrder['status'], ['Success', 'Captured', 'Paid'])) {
                            $orderStatus = Order::$paying;
                            
                            Log::info('Geidea payment successful', [
                                'order_id' => $order->id,
                                'setting_status_to' => $orderStatus,
                            ]);
                        } else {
                            Log::warning('Geidea payment not successful', [
                                'order_id' => $order->id,
                                'geidea_status' => $geideaOrder['status'] ?? 'unknown',
                            ]);
                        }

                        // Update order with payment details
                        $order->update([
                            'status' => $orderStatus,
                            'payment_data' => json_encode([
                                'order_details' => $geideaOrder,
                                'callback_data' => $data,
                                'gateway' => 'Geidea',
                                'verified_at' => now()->toDateTimeString(),
                            ]),
                        ]);
                        
                        Log::info('Geidea order updated', [
                            'order_id' => $order->id,
                            'new_status' => $order->status,
                        ]);
                    } else {
                        Log::error('Geidea order not found in database', [
                            'merchantReferenceId' => $geideaOrder['merchantReferenceId'] ?? 'unknown',
                            'sessionId' => $geideaOrder['sessionId'] ?? 'unknown',
                        ]);
                    }
                } else {
                    Log::error('Geidea order details not found', [
                        'orderId' => $geideaOrderId,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Geidea verification exception', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $data,
                ]);
            }
        } else {
            Log::warning('Geidea callback missing orderId', [
                'data' => $data,
                'checked_paths' => ['orderId', 'order.orderId'],
            ]);
        }

        Log::info('Geidea verify returning order', [
            'order_id' => $order ? $order->id : null,
            'order_status' => $order ? $order->status : null,
        ]);

        return $order;
    }

    /**
     * Fetch order details from Geidea API
     * 
     * @param string $orderId
     * @return array|null
     */
    private function fetchOrderDetails(string $orderId): ?array
    {
        try {
            $response = Http::withOptions(['verify' => false])
                ->withHeaders([
                    'Authorization' => $this->getAuthHeader(),
                    'Accept' => 'application/json',
                ])->get($this->getApiUrl() . '/pgw/api/v1/direct/order/' . $orderId);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Geidea order fetch failed', [
                'order_id' => $orderId,
                'response' => $response->json(),
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('Geidea order fetch exception', [
                'order_id' => $orderId,
                'message' => $e->getMessage(),
            ]);
            
            return null;
        }
    }

    /**
     * Find our order by Geidea order data
     * 
     * @param array $geideaOrder
     * @return Order|null
     */
    private function findOrderByGeideaData(array $geideaOrder): ?Order
    {
        // Try to find by merchant reference ID
        if (!empty($geideaOrder['merchantReferenceId'])) {
            $merchantRef = $geideaOrder['merchantReferenceId'];
            
            // Extract order ID from merchant reference (format: ORDER-{id}-{timestamp})
            if (preg_match('/ORDER-(\d+)-/', $merchantRef, $matches)) {
                $orderId = $matches[1];
                
                $order = Order::find($orderId);
                if ($order) {
                    return $order;
                }
            }
        }

        // Try to find by session ID in payment_data
        $orders = Order::whereNotNull('payment_data')
            ->where('payment_data', 'like', '%"gateway":"Geidea"%')
            ->get();

        foreach ($orders as $order) {
            $paymentData = json_decode($order->payment_data, true);
            
            if (isset($paymentData['session_response']['session']['id']) &&
                isset($geideaOrder['sessionId']) &&
                $paymentData['session_response']['session']['id'] === $geideaOrder['sessionId']) {
                return $order;
            }
        }

        return null;
    }

    /**
     * Refund a payment (full or partial)
     * 
     * @param string $orderId
     * @param float|null $amount
     * @return array|null
     */
    public function refund(string $orderId, ?float $amount = null): ?array
    {
        try {
            $refundData = [
                'orderId' => $orderId,
            ];

            if ($amount !== null) {
                $refundData['amount'] = number_format($amount, 2, '.', '');
            }

            $response = Http::withOptions(['verify' => false])
                ->withHeaders([
                    'Authorization' => $this->getAuthHeader(),
                    'Content-Type' => 'application/json',
                ])->post($this->getApiUrl() . '/pgw/api/v1/direct/refund', $refundData);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Geidea refund failed', [
                'order_id' => $orderId,
                'amount' => $amount,
                'response' => $response->json(),
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('Geidea refund exception', [
                'order_id' => $orderId,
                'amount' => $amount,
                'message' => $e->getMessage(),
            ]);
            
            return null;
        }
    }
}
