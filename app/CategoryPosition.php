<?php

namespace App;

class CategoryPosition extends BaseModel
{
    protected $table = 'category_position';
    protected $fillable = [
        'code', 'category_id', 'order_in_position'
    ];
}
