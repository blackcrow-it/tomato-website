<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends BaseModel
{
    use HasSlug;

    protected $table = 'posts';

    protected $fillable = [
        'title', 'slug', 'thumbnail', 'cover', 'description', 'content', 'view', 'enabled',
        'meta_title', 'meta_description', 'og_title', 'og_description', 'og_image',
        'category_id', 'order_in_category'
    ];

    public function author()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function editor()
    {
        return $this->belongsTo('App\User', 'updated_by', 'id');
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom($this->slug ? 'slug' : 'title')
            ->saveSlugsTo('slug')
            ->allowDuplicateSlugs();
    }

    public static function booted()
    {
        static::creating(function ($post) {
            $post->created_by = auth()->user()->id ?? null;
            $post->updated_by = auth()->user()->id ?? null;
        });

        static::updating(function ($post) {
            $post->updated_by = auth()->user()->id ?? null;
        });
    }

    public function position()
    {
        return $this->hasMany('App\PostPosition', 'post_id', 'id');
    }

    public function getUrlAttribute()
    {
        return route('post', ['slug' => $this->slug, 'id' => $this->id]);
    }

    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id', 'id');
    }

    public function postComments()
    {
        return $this->hasMany('App\PostComment', 'post_id', 'id');
    }
}
