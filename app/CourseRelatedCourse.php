<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseRelatedCourse extends Model
{
    protected $table = 'course_related_courses';
    protected $fillable = [
        'course_id', 'related_course_id'
    ];

    public function course()
    {
        return $this->belongsTo('App\Course', 'course_id', 'id');
    }

    public function related_course()
    {
        return $this->belongsTo('App\Course', 'related_course_id', 'id');
    }
}
