<?php

namespace App;

use Carbon\Carbon;
use Debugbar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model
{
    use HasSlug;
    use SoftDeletes;

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
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnCreate();
    }

    public function getCloudUrl($attr)
    {
        if (empty($this->$attr)) return null;

        if (filter_var($this->$attr, FILTER_VALIDATE_URL)) return $this->$attr;

        return Storage::cloud()->url($this->$attr);
    }

    public static function booted()
    {
        static::creating(function ($post) {
            $post->created_by = auth()->user()->id ?? null;
        });

        static::updating(function ($post) {
            $post->updated_by = auth()->user()->id ?? null;

            $deletePaths = [];

            if ($post->isDirty('thumbnail')) {
                $deletePaths[] = $post->getOriginal('thumbnail');
            }

            if ($post->isDirty('cover')) {
                $deletePaths[] = $post->getOriginal('cover');
            }

            if ($post->isDirty('og_image')) {
                $deletePaths[] = $post->getOriginal('og_image');
            }

            Storage::cloud()->delete($deletePaths);
        });

        static::deleting(function ($post) {
            Storage::cloud()->deleteDirectory('posts/' . $post->id);
        });
    }
}
