<?php

namespace App\Http\Controllers\Frontend;

use App\CourseVideo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;

class VideoController extends Controller
{
    public function getKey($id)
    {
        $video = CourseVideo::findOrFail($id);
        $key = Storage::disk('s3')->get($video->key_path);
        return $key;
    }
}
