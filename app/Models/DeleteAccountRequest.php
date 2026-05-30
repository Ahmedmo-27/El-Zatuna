<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeleteAccountRequest extends Model
{
    protected $table = 'delete_account_requests';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * Clear pending admin-review deletion when the user proves control (e.g. password reset).
     */
    public static function cancelPendingForUserId(?int $userId): void
    {
        if (!empty($userId)) {
            static::where('user_id', $userId)->delete();
        }
    }
}
