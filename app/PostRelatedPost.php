<?php

namespace App;

class PostRelatedPost extends BaseModel
{
    protected $table = 'post_related_posts';
    protected $fillable = [
        'post_id', 'related_post_id'
    ];

    public function post()
    {
        return $this->belongsTo('App\Post', 'post_id', 'id');
    }

    public function related_post()
    {
        return $this->belongsTo('App\Post', 'related_post_id', 'id');
    }
}
