<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChildPartComment extends Model
{
    protected $fillable =[
        'content', 'partcmt_id', 'user_id'
    ];

    public function partComment()
    {
        return $this->belongsTo('App\PartComment', 'partcmt_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
