<?php

namespace App;

class PartYoutube extends BaseModel
{
    protected $table = 'part_youtube';

    protected $fillable = [
        'part_id',
    ];

    public function part()
    {
        return $this->belongsTo('App\Part', 'part_id', 'id');
    }
}
