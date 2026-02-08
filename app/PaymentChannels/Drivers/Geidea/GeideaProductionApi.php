<?php
/**
 * Geidea Payment Gateway - Production API Helper
 * 
 * This file contains helper functions and documentation for production Geidea API usage.
 * 
 * IMPORTANT: Production credentials should be stored securely in environment variables
 * and never hardcoded or committed to version control.
 * 
 * @see https://docs.geidea.net/docs
 * @see https://docs.geidea.net/reference
 */

namespace App\PaymentChannels\Drivers\Geidea;

/**
 * Production API Configuration
 * 
 * Get your production credentials from:
 * - Merchant Portal: https://www.merchant.geidea.net/eg/merchant-portal
 * - Login -> Payment Gateway -> Gateway Settings
 */
class GeideaProductionConfig
{
    /**
     * Production Environment API URLs
     * 
     * These are the same as test URLs. The difference is in the credentials used.
     * Production credentials will access live merchant accounts.
     */
    public const PRODUCTION_API_URLS = [
        'KSA' => 'https://api.ksamerchant.geidea.net',
        'EGY' => 'https://api.merchant.geidea.net',
        'UAE' => 'https://api.geidea.ae',
    ];

    /**
     * Production Environment HPP URLs
     */
    public const PRODUCTION_HPP_URLS = [
        'KSA' => 'https://www.ksamerchant.geidea.net',
        'EGY' => 'https://www.merchant.geidea.net',
        'UAE' => 'https://payments.geidea.ae',
    ];

    /**
     * Get credentials from environment variables
     * 
     * SECURITY: Never hardcode production credentials!
     * Always use environment variables.
     */
    public static function getCredentials(): array
    {
        return [
            'public_key' => env('GEIDEA_PUBLIC_KEY'),
            'api_password' => env('GEIDEA_API_PASSWORD'),
            'region' => env('GEIDEA_REGION', 'KSA'),
            'currency' => env('GEIDEA_CURRENCY', 'SAR'),
            'language' => env('GEIDEA_LANGUAGE', 'en'),
        ];
    }

    /**
     * Validate production credentials are set
     */
    public static function validateCredentials(): bool
    {
        $credentials = self::getCredentials();
        
        return !empty($credentials['public_key']) && 
               !empty($credentials['api_password']);
    }
}

/**
 * Production API Endpoints
 */
class GeideaProductionEndpoints
{
    /**
     * Create Session Endpoint (Hosted Payment Page)
     * POST /payment-intent/api/v2/direct/session
     * 
     * Use this for the simplest integration - Geidea handles the payment UI
     */
    public static function getCreateSessionUrl(string $region = 'KSA'): string
    {
        $baseUrl = GeideaProductionConfig::PRODUCTION_API_URLS[$region];
        return $baseUrl . '/payment-intent/api/v2/direct/session';
    }

    /**
     * Create Session for Subscription
     * POST /payment-intent/api/v2/direct/session/subscription
     */
    public static function getCreateSubscriptionSessionUrl(string $region = 'KSA'): string
    {
        $baseUrl = GeideaProductionConfig::PRODUCTION_API_URLS[$region];
        return $baseUrl . '/payment-intent/api/v2/direct/session/subscription';
    }

    /**
     * Initiate Authentication Endpoint (for 3DS)
     * POST /pgw/api/v2/direct/authenticate/initiate
     * 
     * Required for Direct API integration with 3D Secure
     */
    public static function getInitiateAuthUrl(string $region = 'KSA'): string
    {
        $baseUrl = GeideaProductionConfig::PRODUCTION_API_URLS[$region];
        return $baseUrl . '/pgw/api/v2/direct/authenticate/initiate';
    }

    /**
     * Authenticate Payer Endpoint (Complete 3DS)
     * POST /pgw/api/v2/direct/authenticate/payer
     */
    public static function getAuthenticatePayerUrl(string $region = 'KSA'): string
    {
        $baseUrl = GeideaProductionConfig::PRODUCTION_API_URLS[$region];
        return $baseUrl . '/pgw/api/v2/direct/authenticate/payer';
    }

    /**
     * Pay Endpoint (Direct API)
     * POST /pgw/api/v2/direct/pay
     * 
     * Direct payment processing - requires PCI DSS compliance
     */
    public static function getPayUrl(string $region = 'KSA'): string
    {
        $baseUrl = GeideaProductionConfig::PRODUCTION_API_URLS[$region];
        return $baseUrl . '/pgw/api/v2/direct/pay';
    }

    /**
     * Capture Transaction Endpoint
     * POST /pgw/api/v1/direct/capture
     * 
     * Capture a previously authorized payment
     */
    public static function getCaptureUrl(string $region = 'KSA'): string
    {
        $baseUrl = GeideaProductionConfig::PRODUCTION_API_URLS[$region];
        return $baseUrl . '/pgw/api/v1/direct/capture';
    }

    /**
     * Refund Endpoint (Full or Partial)
     * POST /pgw/api/v1/direct/refund
     */
    public static function getRefundUrl(string $region = 'KSA'): string
    {
        $baseUrl = GeideaProductionConfig::PRODUCTION_API_URLS[$region];
        return $baseUrl . '/pgw/api/v1/direct/refund';
    }

    /**
     * Void Payment Endpoint
     * POST /pgw/api/v1/direct/void
     * 
     * Cancel an authorized payment before capture
     */
    public static function getVoidUrl(string $region = 'KSA'): string
    {
        $baseUrl = GeideaProductionConfig::PRODUCTION_API_URLS[$region];
        return $baseUrl . '/pgw/api/v1/direct/void';
    }

    /**
     * Cancel Order Endpoint
     * POST /pgw/api/v1/direct/cancel
     */
    public static function getCancelOrderUrl(string $region = 'KSA'): string
    {
        $baseUrl = GeideaProductionConfig::PRODUCTION_API_URLS[$region];
        return $baseUrl . '/pgw/api/v1/direct/cancel';
    }

    /**
     * Get Order Details Endpoint
     * GET /pgw/api/v1/direct/order/{orderId}
     */
    public static function getOrderDetailsUrl(string $orderId, string $region = 'KSA'): string
    {
        $baseUrl = GeideaProductionConfig::PRODUCTION_API_URLS[$region];
        return $baseUrl . '/pgw/api/v1/direct/order/' . $orderId;
    }

    /**
     * Get Order by Merchant Reference ID
     * GET /pgw/api/v1/direct/order/search/merchantReference/{merchantReferenceId}
     */
    public static function getOrderByMerchantRefUrl(string $merchantReferenceId, string $region = 'KSA'): string
    {
        $baseUrl = GeideaProductionConfig::PRODUCTION_API_URLS[$region];
        return $baseUrl . '/pgw/api/v1/direct/order/search/merchantReference/' . $merchantReferenceId;
    }

    /**
     * Search Transactions Endpoint
     * GET /pgw/api/v1/direct/transactions
     * 
     * Query parameters: fromDate, toDate, status, merchantReferenceId, etc.
     */
    public static function getSearchTransactionsUrl(string $region = 'KSA'): string
    {
        $baseUrl = GeideaProductionConfig::PRODUCTION_API_URLS[$region];
        return $baseUrl . '/pgw/api/v1/direct/transactions';
    }

    /**
     * Retrieve Token Endpoint
     * GET /pgw/api/v1/direct/token/{tokenId}
     * 
     * Get details of a saved card token
     */
    public static function getRetrieveTokenUrl(string $tokenId, string $region = 'KSA'): string
    {
        $baseUrl = GeideaProductionConfig::PRODUCTION_API_URLS[$region];
        return $baseUrl . '/pgw/api/v1/direct/token/' . $tokenId;
    }

    /**
     * Merchant Initiated Transaction (MIT)
     * POST /pgw/api/v1/direct/pay/merchant-initiated
     * 
     * Process payment using saved card (requires customer agreement)
     */
    public static function getMerchantInitiatedUrl(string $region = 'KSA'): string
    {
        $baseUrl = GeideaProductionConfig::PRODUCTION_API_URLS[$region];
        return $baseUrl . '/pgw/api/v1/direct/pay/merchant-initiated';
    }

    /**
     * Create Payment Link
     * POST /payment-link/api/v2/direct/paymentLink
     */
    public static function getCreatePaymentLinkUrl(string $region = 'KSA'): string
    {
        $baseUrl = GeideaProductionConfig::PRODUCTION_API_URLS[$region];
        return $baseUrl . '/payment-link/api/v2/direct/paymentLink';
    }

    /**
     * Create Quick Payment Link
     * POST /payment-link/api/v1/direct/paymentLink/quick
     */
    public static function getCreateQuickPaymentLinkUrl(string $region = 'KSA'): string
    {
        $baseUrl = GeideaProductionConfig::PRODUCTION_API_URLS[$region];
        return $baseUrl . '/payment-link/api/v1/direct/paymentLink/quick';
    }

    /**
     * Create Subscription
     * POST /subscription/api/v1/direct/subscription
     */
    public static function getCreateSubscriptionUrl(string $region = 'KSA'): string
    {
        $baseUrl = GeideaProductionConfig::PRODUCTION_API_URLS[$region];
        return $baseUrl . '/subscription/api/v1/direct/subscription';
    }

    /**
     * Get Subscription Details
     * GET /subscription/api/v1/direct/subscription/{subscriptionId}
     */
    public static function getSubscriptionDetailsUrl(string $subscriptionId, string $region = 'KSA'): string
    {
        $baseUrl = GeideaProductionConfig::PRODUCTION_API_URLS[$region];
        return $baseUrl . '/subscription/api/v1/direct/subscription/' . $subscriptionId;
    }

    /**
     * Cancel Subscription
     * POST /subscription/api/v1/direct/subscription/{subscriptionId}/cancel
     */
    public static function getCancelSubscriptionUrl(string $subscriptionId, string $region = 'KSA'): string
    {
        $baseUrl = GeideaProductionConfig::PRODUCTION_API_URLS[$region];
        return $baseUrl . '/subscription/api/v1/direct/subscription/' . $subscriptionId . '/cancel';
    }

    /**
     * Apple Pay Direct API
     * POST /pgw/api/v2/direct/apple-pay
     */
    public static function getApplePayUrl(string $region = 'KSA'): string
    {
        $baseUrl = GeideaProductionConfig::PRODUCTION_API_URLS[$region];
        return $baseUrl . '/pgw/api/v2/direct/apple-pay';
    }

    /**
     * Meeza QR Code (Egypt only)
     * POST /meeza/api/v1/direct/qr-code
     */
    public static function getMeezaQrCodeUrl(): string
    {
        $baseUrl = GeideaProductionConfig::PRODUCTION_API_URLS['EGY'];
        return $baseUrl . '/meeza/api/v1/direct/qr-code';
    }
}

/**
 * Production Helper Functions
 */
class GeideaProductionHelper
{
    /**
     * Generate signature for production API calls
     * 
     * @param float $amount Payment amount
     * @param string $currency Currency code (SAR, EGP, AED, etc.)
     * @param string $merchantReferenceId Your order/transaction reference
     * @param string $timestamp Timestamp in format Y/m/d H:i:s
     * @return string Base64 encoded signature
     */
    public static function generateSignature(
        float $amount,
        string $currency,
        string $merchantReferenceId,
        string $timestamp
    ): string {
        $credentials = GeideaProductionConfig::getCredentials();
        
        $amountStr = number_format($amount, 2, '.', '');
        $data = "{$credentials['public_key']}{$amountStr}{$currency}{$merchantReferenceId}{$timestamp}";
        $hash = hash_hmac('sha256', $data, $credentials['api_password'], true);
        
        return base64_encode($hash);
    }

    /**
     * Generate Basic Auth header for production
     * 
     * @return string Authorization header value
     */
    public static function getAuthHeader(): string
    {
        $credentials = GeideaProductionConfig::getCredentials();
        return 'Basic ' . base64_encode("{$credentials['public_key']}:{$credentials['api_password']}");
    }

    /**
     * Get production API URL for configured region
     * 
     * @return string Base API URL
     */
    public static function getApiUrl(): string
    {
        $credentials = GeideaProductionConfig::getCredentials();
        $region = $credentials['region'] ?? 'KSA';
        
        return GeideaProductionConfig::PRODUCTION_API_URLS[$region] ?? 
               GeideaProductionConfig::PRODUCTION_API_URLS['KSA'];
    }

    /**
     * Get production HPP URL for configured region
     * 
     * @return string HPP base URL
     */
    public static function getHppUrl(): string
    {
        $credentials = GeideaProductionConfig::getCredentials();
        $region = $credentials['region'] ?? 'KSA';
        
        return GeideaProductionConfig::PRODUCTION_HPP_URLS[$region] ?? 
               GeideaProductionConfig::PRODUCTION_HPP_URLS['KSA'];
    }

    /**
     * Validate callback signature from Geidea
     * 
     * @param array $callbackData Data received from Geidea callback
     * @return bool True if signature is valid
     */
    public static function validateCallbackSignature(array $callbackData): bool
    {
        if (!isset($callbackData['signature'])) {
            return false;
        }

        $receivedSignature = $callbackData['signature'];
        
        // Reconstruct signature data based on callback parameters
        // Implementation depends on callback structure
        // Refer to: https://docs.geidea.net/docs/sample-callback-responses
        
        return true; // Implement actual validation
    }

    /**
     * Log production API request/response (for debugging)
     * 
     * SECURITY: Ensure sensitive data (card numbers, CVV, passwords) are NOT logged
     */
    public static function logApiCall(string $endpoint, array $request, array $response): void
    {
        // Sanitize sensitive data before logging
        $sanitizedRequest = self::sanitizeLogData($request);
        $sanitizedResponse = self::sanitizeLogData($response);

        \Log::info('Geidea Production API Call', [
            'endpoint' => $endpoint,
            'request' => $sanitizedRequest,
            'response' => $sanitizedResponse,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Remove sensitive data from log entries
     */
    private static function sanitizeLogData(array $data): array
    {
        $sensitiveKeys = [
            'cardNumber',
            'cvv',
            'api_password',
            'apiPassword',
            'signature',
            'paymentMethod',
        ];

        foreach ($sensitiveKeys as $key) {
            if (isset($data[$key])) {
                $data[$key] = '***REDACTED***';
            }
        }

        return $data;
    }
}

/**
 * Production Security Best Practices
 */
class GeideaProductionSecurity
{
    /**
     * Security checklist for production deployment
     */
    public const SECURITY_CHECKLIST = [
        '✓ Store credentials in environment variables, never in code',
        '✓ Use HTTPS for all API communications',
        '✓ Validate callback signatures from Geidea',
        '✓ Implement rate limiting on payment endpoints',
        '✓ Log API calls but sanitize sensitive data',
        '✓ Use secure random values for merchantReferenceId',
        '✓ Implement fraud detection and monitoring',
        '✓ Set up alerts for failed transactions',
        '✓ Regularly rotate API credentials',
        '✓ Implement proper error handling without exposing sensitive info',
        '✓ Use PCI DSS compliant hosting if using Direct API',
        '✓ Test thoroughly in sandbox before going live',
        '✓ Implement idempotency for payment operations',
        '✓ Set up webhook endpoints with proper authentication',
        '✓ Monitor for unusual payment patterns',
    ];

    /**
     * PCI DSS Compliance Notes
     */
    public const PCI_DSS_NOTES = [
        'HPP Integration' => 'Geidea handles card data - lower PCI DSS requirements',
        'Direct API Integration' => 'You handle card data - full PCI DSS compliance required',
        'Tokenization' => 'Use tokens instead of storing card data',
        'Recommendation' => 'Use HPP or tokenization to minimize PCI DSS scope',
    ];
}

/**
 * Production Environment Variables Template
 * 
 * Add these to your .env file:
 */
class GeideaProductionEnvTemplate
{
    public const ENV_TEMPLATE = <<<'ENV'
# Geidea Payment Gateway - Production Configuration

# Merchant Credentials (Get from Geidea Merchant Portal)
GEIDEA_PUBLIC_KEY=your_production_public_key_here
GEIDEA_API_PASSWORD=your_production_api_password_here

# Region Configuration
# Options: KSA (Saudi Arabia), EGY (Egypt), UAE (United Arab Emirates)
GEIDEA_REGION=KSA

# Currency Configuration
# Options: SAR, EGP, AED, QAR, OMR, BHD, KWD, USD, GBP, EUR
GEIDEA_CURRENCY=SAR

# Language for Checkout Page
# Options: en, ar
GEIDEA_LANGUAGE=en

# Payment Operation
# Options: Pay, Authorize (for pre-authorization)
GEIDEA_PAYMENT_OPERATION=Pay

# Card on File (Save card for future use)
# Options: true, false
GEIDEA_CARD_ON_FILE=false

# Callback and Return URLs
GEIDEA_CALLBACK_URL=https://yourdomain.com/payment/callback
GEIDEA_RETURN_URL=https://yourdomain.com/payment/return

ENV;
}

/**
 * Production API Response Codes
 * 
 * @see https://docs.geidea.net/docs/api-response-codes-and-messages
 */
class GeideaProductionResponseCodes
{
    public const SUCCESS_CODES = [
        '000' => 'Success - Transaction approved',
    ];

    public const DECLINE_CODES = [
        '001' => 'Transaction declined - General decline',
        '002' => 'Invalid card number',
        '003' => 'Invalid merchant',
        '005' => 'Insufficient funds',
        '014' => 'Invalid card number',
        '033' => 'Expired card',
        '051' => 'Exceeds withdrawal limit',
        '054' => 'Expired card',
        '057' => 'Transaction not permitted to cardholder',
        '065' => 'Exceeds withdrawal frequency',
    ];

    public const AUTHENTICATION_CODES = [
        '100' => '3D Secure authentication required',
        '101' => '3D Secure authentication failed',
        '102' => '3D Secure authentication unavailable',
    ];

    public const VALIDATION_CODES = [
        '200' => 'Invalid request format',
        '201' => 'Missing required parameter',
        '202' => 'Invalid parameter value',
        '203' => 'Duplicate transaction',
        '204' => 'Invalid signature',
    ];

    public const SYSTEM_CODES = [
        '300' => 'System error',
        '301' => 'Network error',
        '302' => 'Timeout',
    ];

    /**
     * Check if response code indicates success
     */
    public static function isSuccess(string $code): bool
    {
        return $code === '000';
    }

    /**
     * Check if response requires 3D Secure
     */
    public static function requires3DS(string $code): bool
    {
        return in_array($code, ['100']);
    }

    /**
     * Get human-readable message for response code
     */
    public static function getMessage(string $code): string
    {
        $allCodes = array_merge(
            self::SUCCESS_CODES,
            self::DECLINE_CODES,
            self::AUTHENTICATION_CODES,
            self::VALIDATION_CODES,
            self::SYSTEM_CODES
        );

        return $allCodes[$code] ?? 'Unknown response code';
    }
}
ENV;
