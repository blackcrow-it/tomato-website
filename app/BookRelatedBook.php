<?php

namespace App;

class BookRelatedBook extends BaseModel
{
    protected $table = 'book_related_books';
    protected $fillable = [
        'book_id', 'related_book_id'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }

    public function related_book()
    {
        return $this->belongsTo(Book::class, 'related_book_id', 'id');
    }
}
