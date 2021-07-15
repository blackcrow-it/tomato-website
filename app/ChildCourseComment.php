<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChildCourseComment extends Model
{
    protected $fillable = [
        'content', 'coursecmt_id', 'user_id'
    ];

    public function courseComment()
    {
        return $this->belongsTo('App\CourseComment', 'coursecmt_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
