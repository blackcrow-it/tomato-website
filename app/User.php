<?php

namespace App;

use DateTimeInterface;
use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    protected $fillable = [
        'username', 'password', 'name', 'phone', 'birthday', 'address', 'email', 'avatar',
        'login_token'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = mb_strtolower($value);
    }

    public function postComments()
    {
        return $this->hasMany('App\PostComment', 'user_id', 'id');
    }

    public function courseComments()
    {
        return $this->hasMany('App\CourseComment', 'user_id', 'id');
    }

    public function partComments()
    {
        return $this->hasMany('App\PartComment', 'user_id', 'id');
    }

    public function childPostComments()
    {
        return $this->hasMany('App\ChildPostComment', 'user_id', 'id');
    }

    public function childCourseComments()
    {
        return $this->hasMany('App\ChildCourseComment', 'user_id', 'id');
    }

    public function childPartComments()
    {
        return $this->hasMany('App\ChildPartComment', 'user_id', 'id');
    }
}
