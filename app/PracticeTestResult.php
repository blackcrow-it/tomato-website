<?php

namespace App;

class PracticeTestResultAnswer extends BaseModel
{
    protected $table = 'practice_test_results';

    protected $fillable = [
        'score', 'number_of_correct', 'practice_test_id', 'user_id','created_at', 'updated_at'
    ];

    public function question()
    {
        return $this->belongsTo('App\PracticeTestQuestion', 'question_id', 'id');
    }
}
