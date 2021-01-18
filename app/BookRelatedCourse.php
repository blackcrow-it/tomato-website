<?php

namespace App;

class BookRelatedCourse extends BaseModel
{
    protected $table = 'book_related_courses';
    protected $fillable = [
        'book_id', 'related_course_id'
    ];

    public function book()
    {
        return $this->belongsTo('App\Book', 'book_id', 'id');
    }

    public function related_course()
    {
        return $this->belongsTo('App\Course', 'related_course_id', 'id');
    }
}
