<?php

namespace App;

class Recharge extends BaseModel
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

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
