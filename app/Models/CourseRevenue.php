<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseRevenue extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class, 'tutor_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo(Webinar::class, 'course_id', 'id');
    }

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo('App\User', 'student_id', 'id');
    }
}
