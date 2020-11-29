<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teachers';

    protected $fillable = [
        'name', 'avatar', 'description'
    ];

    public function courses()
    {
        return $this->hasMany(Course::class, 'teacher_id', 'id');
    }
}
