<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $table = 'faculties';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function university()
    {
        return $this->belongsTo(University::class, 'university_id', 'id');
    }

    public function courses()
    {
        return $this->hasMany(Webinar::class, 'faculty_id', 'id');
    }
}
