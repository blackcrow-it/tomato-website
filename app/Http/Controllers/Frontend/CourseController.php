<?php

namespace App\Http\Controllers\Frontend;

use App\Cart;
use App\Category;
use App\Constants\ObjectType;
use App\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index($slug)
    {
        $course = Course::firstWhere([
            'slug' => $slug,
            'enabled' => true
        ]);

        if ($course == null) {
            return redirect()->route('home');
        }

        $lessons = $course->lessons()
            ->orderByRaw('CASE WHEN order_in_course > 0 THEN 0 ELSE 1 END, order_in_course ASC')
            ->orderBy('title', 'asc')
            ->get();

        $lessons->map(function ($lesson) {
            $lesson->parts = $lesson->parts()
                ->orderByRaw('CASE WHEN order_in_lesson > 0 THEN 0 ELSE 1 END, order_in_lesson ASC')
                ->orderBy('created_at', 'asc')
                ->get();
        });

        return view('frontend.course.detail', [
            'course' => $course,
            'lessons' => $lessons,
            'breadcrumb' => Category::ancestorsAndSelf($course->category_id),
            'added_to_cart' => Cart::where('type', ObjectType::COURSE)->where('object_id', $course->id)->exists()
        ]);
    }

    public function start($id)
    {
        $course = Course::find($id);
        if ($course == null) return redirect()->route('home');

        $lesson = $course->lessons()
            ->orderByRaw('CASE WHEN order_in_course > 0 THEN 0 ELSE 1 END, order_in_course ASC')
            ->orderBy('title', 'asc')
            ->first();
        if ($lesson == null) return redirect()->route('home');

        $part = $lesson->parts()
            ->orderByRaw('CASE WHEN order_in_lesson > 0 THEN 0 ELSE 1 END, order_in_lesson ASC')
            ->orderBy('created_at', 'asc')
            ->first();
        if ($part == null) return redirect()->route('home');

        return redirect()->route('part', ['id' => $part->id]);
    }
}
