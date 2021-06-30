<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use DB;

class Course extends BaseModel
{
    use HasSlug;

    protected $table = 'courses';

    protected $fillable = [
        'title', 'slug', 'thumbnail', 'cover', 'description', 'content', 'view', 'enabled',
        'meta_title', 'meta_description', 'og_title', 'og_description', 'og_image',
        'category_id', 'order_in_category',
        'price', 'original_price', 'intro_youtube_id',
        'buyer_days_owned',
        'teacher_id', 'level',
        'video_footer_text'
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
        return $this->hasMany('App\CoursePosition', 'course_id', 'id');
    }

    public function getUrlAttribute()
    {
        return route('course', ['slug' => $this->slug, 'id' => $this->id]);
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

    public function combo_course_items()
    {
        return $this->hasMany(ComboCoursesItem::class, 'course_id', 'id');
    }

    // Hàm xoá courses và xoá luôn cả các liên kết để không bị lỗi transaction
    public function delete()
    {
        DB::transaction(function()
        {
            $this->position()->delete();
            $this->lessons()->delete();
            $this->user_courses()->delete();
            $this->combo_course_items()->delete();
            parent::delete();
        });
    }
}
