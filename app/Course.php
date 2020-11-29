<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Course extends Model
{
    use HasSlug;

    protected $table = 'courses';

    protected $fillable = [
        'title', 'slug', 'thumbnail', 'cover', 'description', 'content', 'view', 'enabled',
        'meta_title', 'meta_description', 'og_title', 'og_description', 'og_image',
        'category_id', 'order_in_category',
        'price', 'original_price', 'intro_youtube_id',
        'buyer_days_owned',
        'teacher_id', 'level'
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

    public function position()
    {
        return $this->hasMany('App\CoursePosition', 'course_id', 'id');
    }

    public function getUrlAttribute()
    {
        return route('course', ['slug' => $this->slug]);
    }

    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id', 'id');
    }

    public function lessons()
    {
        return $this->hasMany('App\Lesson', 'course_id', 'id');
    }

    public function user_courses()
    {
        return $this->hasMany('App\UserCourse', 'course_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }
}
