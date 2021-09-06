<?php

namespace App;

class PracticeTestAnswer extends BaseModel
{
    protected $table = 'practice_test_answers';

    protected $fillable = [
        'correct', 'content', 'enabled', 'content','index','question_id','created_at', 'updated_at'
    ];

    public function question()
    {
        return $this->belongsTo('App\PracticeTestQuestion', 'question_id', 'id');
    }
}
