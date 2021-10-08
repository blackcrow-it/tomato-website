<?php

namespace App;

class PracticeTestQuestionSession extends BaseModel
{
    protected $table = 'practice_test_question_session';

    protected $fillable = [
        'name','created_at', 'updated_at'
    ];
}