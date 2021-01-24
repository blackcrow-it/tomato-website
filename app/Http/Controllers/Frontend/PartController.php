<?php

namespace App\Http\Controllers\Frontend;

use App\Category;
use App\Constants\PartType;
use App\CourseRelatedBook;
use App\Http\Controllers\Controller;
use App\Part;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Storage;

class PartController extends Controller
{
    public function index($id)
    {
        $part = Part::find($id);
        if ($part == null) return redirect()->route('home');

        $lesson = $part->lesson;
        if ($lesson == null) return redirect()->route('home');

        $course = $lesson->course;
        if ($course == null) return redirect()->route('home');

        $relatedBooks = CourseRelatedBook::query()
            ->with('related_book')
            ->whereHas('related_book', function (Builder $query) {
                $query->where('enabled', true);
            })
            ->where('course_id', $course->id)
            ->orderBy('id', 'asc')
            ->get()
            ->pluck('related_book');

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

        $data = $part->{'part_' . $part->type};

        return view('frontend.part.' . $part->type, [
            'course' => $course,
            'breadcrumb' => Category::ancestorsAndSelf($course->category_id),
            'lessons' => $lessons,
            'part' => $part,
            'data' => $data,
            'stream_url' => $part->type == PartType::VIDEO ? Storage::disk('s3')->url($data->s3_path . '/hls/playlist.m3u8') : null,
            'related_books' => $relatedBooks
        ]);
    }
}
