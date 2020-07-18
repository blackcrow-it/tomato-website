<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model
{
    use HasSlug;

    protected $table = 'posts';

    protected $fillable = [
        'title', 'slug', 'thumbnail', 'cover', 'description', 'content', 'view', 'enabled',
        'meta_title', 'meta_description', 'og_title', 'og_description', 'og_image',
        'category_id', 'order_in_category'
    ];

    public function owner()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function last_editor()
    {
        return $this->belongsTo('App\User', 'updated_by', 'id');
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
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
}
