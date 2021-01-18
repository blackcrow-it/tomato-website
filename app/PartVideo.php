<?php

namespace App;

class PartVideo extends BaseModel
{
    protected $table = 'part_video';

    protected $fillable = [
        'part_id',
        's3_path',
        'transcode_status'
    ];

    public function part()
    {
        return $this->belongsTo('App\Part', 'part_id', 'id');
    }
}
