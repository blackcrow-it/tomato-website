<?php

namespace App;


class PracticeTestCategory extends BaseModel
{
    protected $table = 'practice_test_categories';

    protected $fillable = [
        'title', 'description', 'parent_id','enabled','type', 'max_score', 'pass_score'
    ];

    public function practice_tests(){
        return $this->hasMany('App\PracticeTest');
    }

    public function parent(){
        return $this->belongsTo('App\PracticeTestCategory','parent_id', 'id');
    }
}
