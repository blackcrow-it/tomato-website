<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Part;
use App\PartVideo;
use Illuminate\Http\Request;
use Storage;

class PartVideoController extends Controller
{
    public function getKey($id)
    {
        \Debugbar::disable();

        $part = Part::findOrFail($id);

        $lesson = $part->lesson;
        if ($lesson == null)return abort(500);

        $course = $lesson->course;
        if ($course == null) return abort(500);

        $partVideo = $part->part_video;
        if ($partVideo == null) return abort(500);

        if ($partVideo->s3_path == null) return abort(500);

        return Storage::disk('s3')->get($partVideo->s3_path . '/secret.key');
    }
}
