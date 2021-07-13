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

    public function getPercentComplete() {
        $total = 0;
        $complete = 0;
        $parts = $this->parts()->get();
        foreach ($parts as $part) {
            if ($part->isProcessedWithThisUser()) $complete += 1;
            $total += 1;
        }
        if ($complete == 0) return 0;
        $percent = round((100 / $total) * $complete);
        return $percent;
    }
}
