<?php

namespace App;

use App\Constants\ObjectType;
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

    public function getPercentComplete() {
        $total = 0;
        $complete = 0;
        $lessons = $this->lessons()->get();
        foreach ($lessons as $lesson) {
            $parts = $lesson->parts()->get();
            foreach ($parts as $part) {
                if ($part->isProcessedWithThisUser()) $complete += 1;
                $total += 1;
            }
        }
        if ($complete == 0) return 0;
        $percent = round((100 / $total) * $complete);
        return $percent;
    }

    public function getAvgRating() {
        $totalStar = 0;
        $ratingsOverview = Rating::query()->where('type', ObjectType::COURSE)->where('object_id', $this->id)->get();
        foreach ($ratingsOverview as $rate) {
            $totalStar += $rate->star;
        }
        if (count($ratingsOverview) > 0) {
            return round($totalStar / count($ratingsOverview), 1);
        } else {
            return 0;
        }
    }

    public function next(){
        return Course::where('id', '>', $this->id)
            ->where('enabled', true)
            ->orderBy('order_in_category','asc')
            ->orderBy('id','asc')
            ->first();
    }

    public function previous(){
        return Course::where('id', '<', $this->id)
            ->where('enabled', true)
            ->orderBy('order_in_category','desc')
            ->orderBy('id','desc')
            ->first();

    }

    public function totalSell() {
        return InvoiceItem::where('object_id', $this->id)
            ->where('type', ObjectType::COURSE)
            ->orderBy('id','desc')
            ->get();
    }
}
