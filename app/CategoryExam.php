<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryExam extends Model
{
    protected $table = 'category_exams';

    protected $fillable = [
        'title', 'description', 'parent_id', 'score'
    ];
}
