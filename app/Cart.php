<?php

namespace App;

class Cart extends BaseModel
{
    protected $table = 'cart';
    protected $fillable = [
        'user_id', 'type', 'object_id', 'amount', 'price'
    ];
}
