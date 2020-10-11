<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartSurvey extends Model
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
