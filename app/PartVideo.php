<?php

namespace App;

class PartVideo extends BaseModel
{
    protected $table = 'part_video';

    protected $fillable = [
        'part_id',
        's3_path',
        'transcode_status',
        'transcode_message',
        'transcode_dir'
    ];

    public function part()
    {
        return $this->belongsTo(Part::class, 'part_id', 'id');
    }
}
