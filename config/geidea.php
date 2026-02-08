<?php

/*
 * This file is part of Geidea Payment Gateway.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /*
     |--------------------------------------------------------------------------
     | Merchant Public Key
     |--------------------------------------------------------------------------
     |
     | Your merchant public key (used as username for Basic Auth).
     | You can find this on your Geidea Merchant's Dashboard - Payment Gateway -> Gateway Settings.
     |
     */

    'public_key' => env('GEIDEA_PUBLIC_KEY', null),

    /*
     |--------------------------------------------------------------------------
     | API Password
     |--------------------------------------------------------------------------
     |
     | Your API password (used as password for Basic Auth).
     | You can find this on your Geidea Merchant's Dashboard - Payment Gateway -> Gateway Settings.
     | WARNING: Keep this secret and never expose it in frontend code.
     |
     */

    'api_password' => env('GEIDEA_API_PASSWORD', null),

    /*
     |--------------------------------------------------------------------------
     | Currency
     |--------------------------------------------------------------------------
     |
     | The currency you registered with Geidea account.
     | Supported values: 'SAR', 'EGP', 'AED', 'QAR', 'OMR', 'BHD', 'KWD', 'USD', 'GBP', 'EUR'
     |
     */

    'currency' => env('GEIDEA_CURRENCY', 'SAR'),

    /*
     |--------------------------------------------------------------------------
     | Region/Environment
     |--------------------------------------------------------------------------
     |
     | The region you registered with Geidea.
     | Supported values: 'KSA', 'EGY', 'UAE'
     |
     */

    'region' => env('GEIDEA_REGION', 'KSA'),

    /*
     |--------------------------------------------------------------------------
     | API Base URLs
     |--------------------------------------------------------------------------
     |
     | The base URLs for different regions.
     | These are automatically selected based on the region setting.
     |
     */

    'api_urls' => [
        'KSA' => 'https://api.ksamerchant.geidea.net',
        'EGY' => 'https://api.merchant.geidea.net',
        'UAE' => 'https://api.geidea.ae',
    ],

    /*
     |--------------------------------------------------------------------------
     | HPP (Hosted Payment Page) URLs
     |--------------------------------------------------------------------------
     |
     | The URLs for the Geidea Checkout script for different regions.
     |
     */

    'hpp_urls' => [
        'KSA' => 'https://www.ksamerchant.geidea.net',
        'EGY' => 'https://www.merchant.geidea.net',
        'UAE' => 'https://payments.geidea.ae',
    ],

    /*
     |--------------------------------------------------------------------------
     | Language
     |--------------------------------------------------------------------------
     |
     | The language to be used on the checkout page.
     | Supported values: 'en', 'ar'
     |
     */

    'language' => env('GEIDEA_LANGUAGE', 'en'),

    /*
     |--------------------------------------------------------------------------
     | Callback URL
     |--------------------------------------------------------------------------
     |
     | The URL where Geidea will send payment status notifications (webhook).
     | This must be a publicly accessible HTTPS URL.
     | For local development, you can use a service like ngrok.
     | Default format: {APP_URL}/payments/verify/Geidea
     |
     */

    'callback_url' => env('GEIDEA_CALLBACK_URL', 'https://elzatuna.com/payments/verify/Geidea'),

    /*
     |--------------------------------------------------------------------------
     | Return URL
     |--------------------------------------------------------------------------
     |
     | The URL where customers will be redirected after completing payment.
     | If not set, defaults to your application's home page.
     |
     */

    'return_url' => env('GEIDEA_RETURN_URL', 'https://elzatuna.com'),

    /*
     |--------------------------------------------------------------------------
     | Payment Operation
     |--------------------------------------------------------------------------
     |
     | The type of payment operation to be performed.
     | Supported values: 'Pay', 'Authorize' (for pre-authorization)
     | Default: 'Pay'
     |
     */

    'payment_operation' => env('GEIDEA_PAYMENT_OPERATION', 'Pay'),

    /*
     |--------------------------------------------------------------------------
     | Card on File
     |--------------------------------------------------------------------------
     |
     | Whether to store the payment method for future use.
     | If true, Geidea will save the card and return a tokenId.
     |
     */

    'card_on_file' => env('GEIDEA_CARD_ON_FILE', false),

];
