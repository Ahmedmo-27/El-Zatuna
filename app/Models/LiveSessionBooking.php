<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class LiveSessionBooking extends Model
{
    protected $table = 'live_session_bookings';

    protected $guarded = ['id'];

    public function liveSession()
    {
        return $this->belongsTo(LiveSession::class, 'live_session_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function user()
    {
        return $this->student();
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }
}
