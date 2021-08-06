<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResultExam extends Model
{
    protected $table = 'result_exams';

    protected $fillable = [
        'time', 'score', 'date_exam', 'is_pass', 'test_exam_id', 'user_id'
    ];

    public function testExam()
    {
        return $this->belongsTo(TestExam::class, 'test_exam_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
