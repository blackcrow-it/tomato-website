<?php

namespace App;

class PracticeTest extends BaseModel
{
    protected $table = 'practice_tests';

    protected $fillable = [
        'title', 'description','slug', 'duration', 'start_time', 'end_time','loop','loop_days','date', 'enabled','max_score_override','pass_score_override','created_at', 'updated_at', 'category_id'
    ];

    public function level()
    {
        return $this->belongsTo('App\PracticeTestCategory','category_id', 'id');
    }

    public function questions(){
        return $this->hasMany('App\PracticeTestQuestion');
    }
    
    public function shifts(){
        return $this->hasMany('App\PracticeTestShift');
    }
}
