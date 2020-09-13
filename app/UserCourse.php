<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCourse extends Model
{
    protected $table = 'user_courses';

    protected $fillable = [
        'user_id', 'course_id', 'expires_on'
    ];

    protected $dates = [
        'expires_on'
    ];

    public function course()
    {
        return $this->belongsTo('App\Course', 'course_id', 'id');
    }
}
