<?php

namespace App;

class PracticeTestSessionResult extends BaseModel
{
    protected $table = 'practice_test_session_results';

    protected $fillable = [
        'score', 'practice_test_results_id', 'practice_test_session_id','max_score','pass_score','created_at', 'updated_at'
    ];

    public function result()
    {
        return $this->belongsTo('App\PracticeTestResult', 'practice_test_results_id', 'id');
    }

    public function session()
    {
        return $this->belongsTo('App\PracticeTestQuestionSession', 'practice_test_session_id', 'id');
    }
}
