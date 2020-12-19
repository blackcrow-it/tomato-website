<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $table = 'promo';

    protected $fillable = [
        'code',
        'type',
        'value',
        'expires_on',
        'used_many_times'
    ];

    protected $dates = [
        'expires_on'
    ];

    protected $casts = [
        'expires_on' => 'datetime:Y-m-d H:i',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'promo_id', 'id');
    }
}
