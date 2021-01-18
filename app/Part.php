<?php

namespace App;

class Part extends BaseModel
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
        return $this->hasOne('App\PartVideo', 'part_id', 'id')->withDefault();
    }

    public function part_youtube()
    {
        return $this->hasOne('App\PartYoutube', 'part_id', 'id')->withDefault();
    }

    public function part_content()
    {
        return $this->hasOne('App\PartContent', 'part_id', 'id')->withDefault();
    }

    public function part_test()
    {
        return $this->hasOne('App\PartTest', 'part_id', 'id')->withDefault();
    }

    public function part_survey()
    {
        return $this->hasOne('App\PartSurvey', 'part_id', 'id')->withDefault();
    }

    public function getUrlAttribute()
    {
        return route('part', ['id' => $this->id]);
    }
}
