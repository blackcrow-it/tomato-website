<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
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
        'meta_title', 'meta_description', 'og_title', 'og_description', 'og_image',
        'type', 'url'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['slug', 'title'])
            ->saveSlugsTo('slug');
    }

    public static function booted()
    {
        static::saving(function ($category) {
            if ($category->parent_id != null) {
                $category->type = $category->parent->type;
            }
        });

        static::updating(function ($category) {
            if (!$category->isDirty('type')) return;

            Post::whereIn('category_id', $category->descendants()->pluck('id'))->update([
                'category_id' => null,
                'order_in_category' => 0
            ]);

            $category->descendants()->update([
                'type' => $category->type
            ]);
        });

        static::deleting(function ($category) {
            // Cập nhật category_id thành null cho các bài viết thuộc danh mục được xóa
            Post::whereIn('category_id', $category->descendants()->pluck('id'))->update([
                'category_id' => null,
                'order_in_category' => 0
            ]);

            $category->descendants()->delete();
        });
    }

    public function position()
    {
        return $this->hasMany('App\CategoryPosition', 'category_id', 'id');
    }

    public function getUrlAttribute()
    {
        return $this->attributes['url'] ?? route('category', ['slug' => $this->slug]);
    }
}
