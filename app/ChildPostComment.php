<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChildPostComment extends Model
{
    protected $fillable = [
        'content', 'postcmt_id', 'user_id'
    ];

    public function postComment()
    {
        return $this->belongsTo('App\PostComment', 'postcmt_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
