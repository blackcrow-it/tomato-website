<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    protected $table = 'test_results';

    protected $fillable = [
        'test_id', 'user_id', 'score', 'is_pass'
    ];

    public function test()
    {
        return $this->belongsTo(PartTest::class, 'test_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function setCurrentUser()
    {
        if (auth()) {
            $this->user_id = auth()->id();
        }
    }
}
