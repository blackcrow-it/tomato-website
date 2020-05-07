<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use HasSlug;
    use NodeTrait;

    protected $table = 'categories';

    protected $fillable = [
        'parent_id', 'title', 'slug', 'icon', 'cover', 'description',
        'meta_title', 'meta_description', 'og_title', 'og_description', 'og_image'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getCloudUrl($attr)
    {
        if (empty($this->$attr)) return null;

        if (filter_var($this->$attr, FILTER_VALIDATE_URL)) return $this->$attr;

        return Storage::cloud()->url($this->$attr);
    }

    public static function booted()
    {
        static::updating(function ($category) {
            $deletePaths = [];

            if ($category->isDirty('cover')) {
                $deletePaths[] = $category->getOriginal('cover');
            }

            if ($category->isDirty('og_image')) {
                $deletePaths[] = $category->getOriginal('og_image');
            }

            Storage::cloud()->delete($deletePaths);
        });

        static::deleting(function ($category) {
            Storage::cloud()->delete([
                $category->cover,
                $category->og_image,
            ]);

            Post::where('category_id', $category->id)->update([
                'category_id' => null,
                'order_in_category' => 0
            ]);
        });
    }
}
