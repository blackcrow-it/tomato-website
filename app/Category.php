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

    const TYPE_COURSE = 'course';
    const TYPE_POST = 'post';

    protected $fillable = [
        'parent_id', 'title', 'slug', 'icon', 'cover', 'description',
        'meta_title', 'meta_description', 'og_title', 'og_description', 'og_image',
        'type'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public static function booted()
    {
        static::deleting(function ($category) {
            Post::where('category_id', $category->id)->update([
                'category_id' => null,
                'order_in_category' => 0
            ]);
        });
    }
}
