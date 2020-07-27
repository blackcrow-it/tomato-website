<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseVideo extends Model
{
    protected $table = 'course_videos';

    protected $fillable = [
        'course_id', 'title', 'thumbnail'
    ];

    public function course() {
        return $this->belongsTo('App\Course', 'course_id', 'id');
    }
}
