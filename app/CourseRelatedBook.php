<?php

namespace App;

class CourseRelatedBook extends BaseModel
{
    protected $table = 'course_related_books';
    protected $fillable = [
        'course_id', 'related_book_id'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function related_book()
    {
        return $this->belongsTo(Book::class, 'related_book_id', 'id');
    }
}
