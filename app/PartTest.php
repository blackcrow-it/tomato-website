<?php

namespace App;

class PartTest extends BaseModel
{
    protected $table = 'part_test';

    protected $fillable = [
        'part_id',
        'data',
        'correct_requirement',
        'random_enabled',
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function part()
    {
        return $this->belongsTo('App\Part', 'part_id', 'id');
    }
}
