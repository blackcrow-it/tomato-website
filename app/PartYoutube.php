<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartYoutube extends Model
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
