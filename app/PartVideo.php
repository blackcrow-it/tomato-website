<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartVideo extends Model
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
