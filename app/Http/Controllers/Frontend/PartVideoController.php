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
    public function getKey(Request $request, $id)
    {
        \Debugbar::disable();
		
		if (
			// Cho phép request tới từ iPhone
			preg_match("/iPhone|Android|iPad|iPod|webOS/", $request->header('User-Agent')) !== 1 &&
			// Chặn tất cả các request có header Sec-Fetch-Site khác same-origin
			$request->header('Sec-Fetch-Site') != 'same-origin'
		) {
			return response('Hi.', 500);
		}

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
