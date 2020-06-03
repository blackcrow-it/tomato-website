<?php

namespace App\Http\Controllers;

use App\CourseVideo;
use App\Jobs\ConvertToHlsJob;
use Carbon\Carbon;
use Debugbar;
use Illuminate\Http\Request;
use Storage;

class TestController extends Controller
{
    public function index()
    {
        Debugbar::disable();

        $video = CourseVideo::first();
        if (!$video) return;

        // ConvertToHlsJob::dispatch($video);

        return view('test', [
            'video_url' => Storage::disk('s3')->url($video->m3u8_path)
        ]);
    }
}
