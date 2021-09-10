<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use DB;

class ComboCourses extends Model
{
    use HasSlug;

    protected $table = 'combo_courses';

    protected $fillable = [
        'title', 'slug', 'thumbnail', 'cover', 'description', 'content', 'enabled', 'price',
        'meta_title', 'meta_description', 'og_title', 'og_description', 'og_image', 'category_id', 'order_in_category',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom($this->slug ? 'slug' : 'title')
            ->saveSlugsTo('slug')
            ->allowDuplicateSlugs();
    }

    public function getUrlAttribute()
    {
        return route('combo_course', ['slug' => $this->slug, 'id' => $this->id]);
    }

    public function items()
    {
        return $this->hasMany(ComboCoursesItem::class, 'combo_courses_id', 'id');
    }

    // Hàm xoá combo courses và xoá luôn cả combo course items để không bị lỗi transaction
    public function delete()
    {
        DB::transaction(function()
        {
            $this->items()->delete();
            parent::delete();
        });
    }

    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id', 'id');
    }
}
