<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRegisteredDevice extends Model
{
    public $timestamps = false;

    protected $table = 'user_registered_devices';

    protected $guarded = ['id'];

    /*
     * Relations
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function activeSessions()
    {
        return $this->hasMany('App\Models\UserActiveSession', 'device_fingerprint', 'device_fingerprint');
    }

    /*
     * Methods
     */
    public function getFormattedFirstRegistered()
    {
        if (empty($this->first_registered_at)) {
            return trans('public.never');
        }

        return dateTimeFormat($this->first_registered_at, 'j M Y H:i');
    }

    public function getFormattedLastUsed()
    {
        if (empty($this->last_used_at)) {
            return trans('public.never');
        }

        return dateTimeFormat($this->last_used_at, 'j M Y H:i');
    }

    public function updateLastUsed()
    {
        $this->update([
            'last_used_at' => time(),
            'login_count' => $this->login_count + 1,
        ]);
    }

    public function getDeviceIcon()
    {
        $platform = strtolower($this->platform ?? '');

        if (in_array($platform, ['mobile', 'smartphone'])) {
            return 'mobile';
        } elseif ($platform === 'tablet') {
            return 'tablet';
        } else {
            return 'desktop';
        }
    }

    public function getDeviceDisplayName()
    {
        if (!empty($this->device_name) && trim($this->device_name) !== '' && trim($this->device_name) !== 'on') {
            return $this->device_name;
        }

        $parts = [];
        
        if (!empty($this->browser) && $this->browser !== 'Unknown Browser' && trim($this->browser) !== '') {
            $parts[] = $this->browser;
        }
        
        if (!empty($this->os) && $this->os !== 'Unknown OS' && trim($this->os) !== '') {
            $parts[] = trans('public.on') . ' ' . $this->os;
        }

        if (empty($parts)) {
            return trans('public.unknown_device');
        }

        return implode(' ', $parts);
    }

    /**
     * Check if this is the user's only registered device
     *
     * @return bool
     */
    public function isOnlyDevice()
    {
        return self::where('user_id', $this->user_id)->count() === 1;
    }
}
