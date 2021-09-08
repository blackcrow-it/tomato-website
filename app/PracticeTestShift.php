<?php

namespace App;

class PracticeTestShift extends BaseModel
{
    protected $table = 'practice_test_shifts';

    protected $fillable = [
        'start_time', 'end_time','practice_test_id','created_at', 'updated_at'
    ];

    public function practiceTest()
    {
        return $this->belongsTo('App\PracticeTest', 'practice_test_id', 'id');
    }

}