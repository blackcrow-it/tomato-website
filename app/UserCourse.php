<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class UserCourse extends BaseModel
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
