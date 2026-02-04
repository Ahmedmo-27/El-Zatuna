<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RegistrationVerificationToken extends Model
{
    protected $table = 'registration_verification_tokens';

    protected $guarded = ['id'];

    protected $casts = [
        'data' => 'array',
        'expires_at' => 'datetime',
    ];

    /**
     * Generate a secure encrypted token
     *
     * @param array $data
     * @param int $expiryMinutes
     * @return string
     */
    public static function generateToken(array $data, int $expiryMinutes = 15): string
    {
        $token = Str::random(64);
        
        self::create([
            'token' => hash('sha256', $token),
            'data' => $data,
            'expires_at' => now()->addMinutes($expiryMinutes),
            'used' => false,
        ]);

        return $token;
    }

    /**
     * Verify and retrieve token data
     *
     * @param string $token
     * @return array|null
     */
    public static function verifyToken(string $token): ?array
    {
        $hashedToken = hash('sha256', $token);
        
        $record = self::where('token', $hashedToken)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return null;
        }

        return $record->data;
    }

    /**
     * Mark token as used
     *
     * @param string $token
     * @return bool
     */
    public static function markAsUsed(string $token): bool
    {
        $hashedToken = hash('sha256', $token);
        
        $record = self::where('token', $hashedToken)->first();

        if ($record) {
            $record->update(['used' => true]);
            return true;
        }

        return false;
    }

    /**
     * Clean up expired tokens
     *
     * @return int
     */
    public static function cleanupExpired(): int
    {
        return self::where('expires_at', '<', now())->delete();
    }

    /**
     * Relation to user (if needed for tracking)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
