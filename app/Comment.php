<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $fillable = [
        'user_id', 'type', 'object_id', 'parent_id', 'content', 'approved'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function comments_child()
    {
        return Comment::query()
            ->with(array('user' => function($query) {
                $query->select('id', 'name', 'avatar');
            }))
            ->where('parent_id', $this->id)
            ->orderBy('created_at', 'ASC')
            ->get();
    }
}
