<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recharge extends Model
{
    protected $table = 'recharge';
    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'status',
        'trans_id',
        'request_data',
        'callback_data',
        'notify_data',
    ];

    protected $hidden = [
        'request_data', 'callback_data', 'notify_data'
    ];
}
