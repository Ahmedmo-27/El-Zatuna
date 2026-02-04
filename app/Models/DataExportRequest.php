<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataExportRequest extends Model
{
    protected $table = 'data_export_requests';

    protected $guarded = ['id'];

    protected $dates = [
        'requested_at',
        'estimated_ready_at',
        'completed_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'estimated_ready_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that requested the data export
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
