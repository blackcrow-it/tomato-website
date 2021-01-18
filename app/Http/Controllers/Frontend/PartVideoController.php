<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Part;
use App\PartVideo;
use Auth;
use Illuminate\Http\Request;
use Storage;

class PartVideoController extends Controller
{
    public function getKey($id)
    {
        \Debugbar::disable();

        if (!Auth::check()) return response('Unauthorized.', 500);

        $part = Part::findOrFail($id);

        $lesson = $part->lesson;
        if ($lesson == null) return response('Lesson not found.', 500);

        $course = $lesson->course;
        if ($course == null) return response('Course not found.', 500);

        $partVideo = $part->part_video;
        if ($partVideo == null) return response('Video not found.', 500);

        if ($partVideo->s3_path == null) return response('Stream folder not found.', 500);

        $key = Storage::disk('s3')->get($partVideo->s3_path . '/secret.key');

        return $key;
    }
}
