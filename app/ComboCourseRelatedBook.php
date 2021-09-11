<?php

namespace App;

class ComboCourseRelatedBook extends BaseModel
{
    protected $table = 'combo_course_related_books';
    protected $fillable = [
        'combo_course_id', 'related_book_id'
    ];

    public function combo_course()
    {
        return $this->belongsTo(ComboCourses::class, 'combo_course_id', 'id');
    }

    public function related_book()
    {
        return $this->belongsTo(Book::class, 'related_book_id', 'id');
    }
}
