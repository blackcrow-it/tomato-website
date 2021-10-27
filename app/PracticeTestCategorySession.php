<?php

namespace App;


class PracticeTestCategorySession extends BaseModel
{
    protected $table = 'practice_test_category_session';

    protected $fillable = [
        'category_id', 'question_session_id','alias'
    ];

    public function category(){
        return $this->belongsTo('App\PracticeTestCategory','category_id', 'id');
    }

    public function session(){
        return $this->belongsTo('App\PracticeTestQuestionSession','question_session_id', 'id');
    }
}
