<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $table = 'parts';

    protected $fillable = [
        'title', 'enabled', 'order_in_lesson', 'lesson_id', 'type'
    ];

    public function lesson()
    {
        return $this->belongsTo('App\Lesson', 'lesson_id', 'id');
    }

    public function part_video()
    {
        return $this->hasOne('App\PartVideo', 'part_id', 'id');
    }
}
