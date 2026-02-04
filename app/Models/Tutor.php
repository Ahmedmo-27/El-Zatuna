<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function courseRevenues()
    {
        return $this->hasMany(CourseRevenue::class, 'tutor_id', 'id');
    }
}
