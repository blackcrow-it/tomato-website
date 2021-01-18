<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCourse extends Model
{
    use SoftDeletes;

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
