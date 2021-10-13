<?php

namespace App;

class PracticeTestResult extends BaseModel
{
    protected $table = 'practice_test_results';

    protected $fillable = [
        'score', 'number_of_correct', 'duration','max_score','pass_score', 'practice_test_id', 'user_id','created_at', 'updated_at'
    ];

    public function question()
    {
        return $this->belongsTo('App\PracticeTestQuestion', 'question_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
