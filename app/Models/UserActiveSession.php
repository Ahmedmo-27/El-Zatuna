<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActiveSession extends Model
{
    public $timestamps = false;

    protected $table = 'user_active_sessions';

    protected $guarded = ['id'];

    /*
     * Relations
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /*
     * Scopes
     */
    public function scopeWeb($query)
    {
        return $query->where('session_type', 'web');
    }

    public function scopeApi($query)
    {
        return $query->where('session_type', 'api');
    }

    public function scopeActive($query, $minutesThreshold = 30)
    {
        return $query->where('last_activity', '>=', now()->subMinutes($minutesThreshold));
    }

    /*
     * Methods
     */
    public function getFormattedLastActivity()
    {
        if (empty($this->last_activity)) {
            return trans('public.never');
        }

        // last_activity is stored as Unix timestamp (integer)
        return dateTimeFormat($this->last_activity, 'j M Y H:i');
    }

    public function isActive($minutesThreshold = 30)
    {
        if (empty($this->last_activity)) {
            return false;
        }

        // last_activity is stored as Unix timestamp (integer)
        $threshold = now()->subMinutes($minutesThreshold)->timestamp;
        return $this->last_activity >= $threshold;
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
}
