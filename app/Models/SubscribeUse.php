<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscribeUse extends Model
{
    protected $table = 'subscribe_uses';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public function subscribe()
    {
        return $this->belongsTo('App\Models\Subscribe', 'subscribe_id', 'id');
    }

    public function sale()
    {
        return $this->hasOne('App\Models\Sale', 'id', 'sale_id');
    }

    public function installmentOrder()
    {
        return $this->belongsTo('App\Models\InstallmentOrder', 'installment_order_id', 'id');
    }

    public function scopeForItem($query, int $userId, int $subscribeId, string $itemType, ?int $webinarId = null, ?int $chapterId = null)
    {
        $query->where('user_id', $userId)
            ->where('subscribe_id', $subscribeId)
            ->where('item_type', $itemType);

        if (!is_null($webinarId)) {
            $query->where('webinar_id', $webinarId);
        }

        if (!is_null($chapterId)) {
            $query->where('chapter_id', $chapterId);
        }

        return $query;
    }
}
