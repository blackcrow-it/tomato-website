<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseVideo extends Model
{
    protected $table = 'course_videos';

    protected $fillable = [
        'course_id', 'title', 'original_path', 'm3u8_path'
    ];
}