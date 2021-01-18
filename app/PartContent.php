<?php

namespace App;

class PartContent extends BaseModel
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
