<?php

namespace App;

class CoursePosition extends BaseModel
{
    protected $table = 'course_position';
    protected $fillable = [
        'code', 'course_id', 'order_in_position'
    ];
}
