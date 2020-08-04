<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoursePosition extends Model
{
    protected $table = 'course_position';
    protected $fillable = [
        'code', 'course_id', 'order_in_position'
    ];
}
