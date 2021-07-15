<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseComment extends Model
{
    protected $fillable = [
        'content', 'course_id', 'user_id'
    ];

    public function course()
    {
        return $this->belongsTo('App\Course', 'course_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function childCourseComments()
    {
        return $this->hasMany('App\ChildCourseComment', 'coursecmt_id', 'id');
    }
}
