<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookRelatedCourse extends Model
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
