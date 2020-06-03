<?php

namespace App\Http\Controllers;

use App\CourseVideo;
use App\Jobs\ConvertToHlsJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Storage;

class TestController extends Controller
{
    public function index()
    {
        $video = CourseVideo::first();
        if (!$video) return;

        // ConvertToHlsJob::dispatch($video);
        // $allFiles = Storage::disk('s3')->allFiles('hls/' . $video->course_id);
        // foreach ($allFiles as $path) {
        //     Storage::disk('s3')->setVisibility($path, 'public');
        // }

        return view('test', [
            'video_url' => Storage::disk('s3')->url('hls/' . $video->course_id . '/playlist.m3u8')
        ]);
    }
}
