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
        if ( empty($value) ) { // will check for empty string
            $this->attributes['email'] = NULL;
        } else {
            $this->attributes['email'] = mb_strtolower($value);
        }
    }

    public function passwordSecurity()
    {
        return $this->hasOne(PasswordSecurity::class)->orderBy('id','DESC');
    }
}
