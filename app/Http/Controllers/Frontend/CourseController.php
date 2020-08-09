<?php

namespace App\Http\Controllers\Frontend;

use App\Category;
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

        return view('frontend.course.detail', [
            'course' => $course,
            'breadcrumb' => Category::ancestorsAndSelf($course->category_id),
        ]);
    }
}
