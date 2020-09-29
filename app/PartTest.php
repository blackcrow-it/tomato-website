<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartTest extends Model
{
    protected $table = 'part_test';

    protected $fillable = [
        'part_id',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function part()
    {
        return $this->belongsTo('App\Part', 'part_id', 'id');
    }
}
