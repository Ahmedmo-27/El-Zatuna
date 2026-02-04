<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    protected $table = 'universities';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function faculties()
    {
        return $this->hasMany(Faculty::class, 'university_id', 'id');
    }

    public function courses()
    {
        return $this->hasMany(Webinar::class, 'university_id', 'id');
    }
}
