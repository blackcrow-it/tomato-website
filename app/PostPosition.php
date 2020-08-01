<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostPosition extends Model
{
    protected $table = 'post_position';
    protected $fillable = [
        'code', 'post_id', 'order_in_position'
    ];
}
