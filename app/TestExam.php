<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestExam extends Model
{
    protected $table = 'test_exams';

    protected $fillable = [
        'title', 'description', 'data', 'time', 'date_exam', 'status', 'score', 'started_at', 'ended_at', 'related_course', 'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(CategoryExam::class, 'category_id', 'id');
    }
}
