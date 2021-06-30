<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComboCoursesItem extends Model
{
    protected $table = 'combo_courses_items';

    protected $fillable = [
        'combo_courses_id', 'course_id'
    ];

    public function combo_courses()
    {
        return $this->belongsTo(ComboCourses::class, 'combo_courses_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }
}
