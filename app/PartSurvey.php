<?php

namespace App;

class PartSurvey extends BaseModel
{
    protected $table = 'part_survey';

    protected $fillable = [
        'part_id',
        'data',
        'description',
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function part()
    {
        return $this->belongsTo('App\Part', 'part_id', 'id');
    }
}
