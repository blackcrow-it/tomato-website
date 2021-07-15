<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartComment extends Model
{
    protected $fillable = [
        'content', 'part_id', 'user_id'
    ];

    public function part()
    {
        return $this->belongsTo('App\Part', 'part_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function childPartComments()
    {
        return $this->hasMany('App\ChildPartComment', 'partcmt_id', 'id');
    }
}
