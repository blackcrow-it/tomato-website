<?php

namespace App;

class Lesson extends BaseModel
{
    protected $table = 'lessons';

    protected $fillable = [
        'title', 'enabled', 'order_in_course', 'course_id'
    ];

    public function course()
    {
        return $this->belongsTo('App\Course', 'course_id', 'id');
    }

    public function parts() {
        return $this->hasMany('App\Part', 'lesson_id', 'id');
    }
}
