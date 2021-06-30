<?php

namespace App;

class Teacher extends BaseModel
{
    protected $table = 'teachers';

    protected $fillable = [
        'name', 'avatar', 'description', 'email'
    ];

    public function courses()
    {
        return $this->hasMany(Course::class, 'teacher_id', 'id');
    }
}
