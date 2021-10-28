<?php

namespace App;

class PracticeTestResult extends BaseModel
{
    protected $table = 'practice_test_results';

    protected $fillable = [
        'score', 'number_of_correct', 'duration','test_date','max_score','pass_score', 'practice_test_id','is_pass', 'user_id','created_at', 'updated_at'
    ];

    public function practiceTest()
    {
        return $this->belongsTo('App\PracticeTest', 'practice_test_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function section_results(){
        return $this->hasMany('App\PracticeTestSessionResult','practice_test_results_id', 'id');
    }

}
