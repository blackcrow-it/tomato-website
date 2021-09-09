<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserComboCourse extends Model
{
    use SoftDeletes;

    protected $table = 'user_combos_course';

    protected $fillable = [
        'user_id', 'combo_course_id'
    ];

    public function combo_course()
    {
        return $this->belongsTo(ComboCourses::class, 'combo_course_id', 'id');
    }
}
