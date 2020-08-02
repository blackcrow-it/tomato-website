<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryPosition extends Model
{
    protected $table = 'category_position';
    protected $fillable = [
        'code', 'category_id', 'order_in_position'
    ];
}
