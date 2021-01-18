<?php

namespace App;

class BookPosition extends BaseModel
{
    protected $table = 'book_position';
    protected $fillable = [
        'code', 'book_id', 'order_in_position'
    ];
}
