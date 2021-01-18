<?php

namespace App;

class PostPosition extends BaseModel
{
    protected $table = 'post_position';
    protected $fillable = [
        'code', 'post_id', 'order_in_position'
    ];
}
