<?php

namespace App;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCourse extends Model
{
    use SoftDeletes;

    protected $table = 'user_courses';

    protected $fillable = [
        'user_id', 'course_id', 'expires_on'
    ];

    protected $dates = [
        'expires_on'
    ];

    protected function serializeDate(DateTimeInterface $date)
	{
		return $date->format('Y-m-d H:i:s');
	}

    public function course()
    {
        return $this->belongsTo('App\Course', 'course_id', 'id');
    }
}
