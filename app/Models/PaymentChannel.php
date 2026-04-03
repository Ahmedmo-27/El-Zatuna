<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentChannel extends Model
{
    protected $table = 'payment_channels';
    protected $guarded = ['id'];
    public $timestamps = false;

    /** Only Geidea is supported as an online payment gateway. */
    public static $classes = [
        'Geidea',
    ];

    /**
     * Gateways that return a view/response instead of a redirect URL (none for Geidea).
     *
     * @var array<int, string>
     */
    public static $gatewayIgnoreRedirect = [];

    public static $geidea = 'Geidea';

    public function getCredentialsAttribute()
    {
        $credentials = $this->attributes['credentials'];

        if (!empty($credentials)) {
            $credentials = json_decode($credentials, true);
        }

        return $credentials;
    }

    public function getCurrenciesAttribute()
    {
        if (!empty($this->attributes['currencies'])) {
            return json_decode($this->attributes['currencies'], true);
        }

        return [];
    }
}
