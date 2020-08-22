<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartContent extends Model
{
    protected $table = 'part_content';

    protected $fillable = [
        'part_id',
    ];

    public function part()
    {
        return $this->belongsTo('App\Part', 'part_id', 'id');
    }
}
