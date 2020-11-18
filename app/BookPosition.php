<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookPosition extends Model
{
    protected $table = 'book_position';
    protected $fillable = [
        'code', 'book_id', 'order_in_position'
    ];
}
