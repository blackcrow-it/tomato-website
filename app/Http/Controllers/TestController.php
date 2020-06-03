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

        $driver = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
        dd($driver);

        // ConvertToHlsJob::dispatch($video);

        // return view('test', [
        //     'video_url' => Storage::disk('s3')->url('hls/' . $video->course_id . '/playlist.m3u8')
        // ]);
    }
}
