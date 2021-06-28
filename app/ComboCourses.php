<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\SlugOptions;

class ComboCourses extends Model
{
    protected $table = 'combo_courses';

    protected $fillable = [
        'title', 'slug', 'thumbnail', 'cover', 'description', 'content', 'enabled',
        'meta_title', 'meta_description', 'og_title', 'og_description', 'og_image'
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
        return route('course', ['slug' => $this->slug, 'id' => $this->id]);
    }

    public function items()
    {
        return $this->hasMany(ComboCoursesItem::class, 'combo_courses_id', 'id');
    }
}
