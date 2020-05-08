<?php

namespace App\Http\Controllers\Frontend;

use App\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function detail($slug)
    {
        $course = Course::where('slug', $slug)->firstOrFail();

        return view('frontend.course.detail', [
            'course' => $course
        ]);
    }
}
