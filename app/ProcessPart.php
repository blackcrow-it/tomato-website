<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcessPart extends Model
{
    protected $table = 'process_parts';
    protected $fillable = [
        'part_id', 'user_id', 'is_check'
    ];
}
