<?php

namespace App;

class PracticeTestQuestion extends BaseModel
{
    protected $table = 'practice_test_questions';

    protected $fillable = [
        'type', 'enabled', 'level', 'content', 'score','practice_test_id','created_at', 'updated_at', 'question_session_id'
    ];

    public function practiceTest()
    {
        return $this->belongsTo('App\PracticeTest', 'practice_test_id', 'id');
    }

    public function answers(){
        return $this->hasMany('App\PracticeTestAnswer');
    }

}