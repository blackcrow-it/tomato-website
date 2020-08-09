<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostRelatedCourse extends Model
{
    protected $table = 'post_related_courses';
    protected $fillable = [
        'post_id', 'related_course_id'
    ];

    public function post()
    {
        return $this->belongsTo('App\Post', 'post_id', 'id');
    }

    public function related_course()
    {
        return $this->belongsTo('App\Course', 'related_course_id', 'id');
    }
}
