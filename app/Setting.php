<?php

namespace App;

class Setting extends BaseModel
{
    protected $table = 'settings';
    protected $fillable = [
        'key', 'value'
    ];
}
