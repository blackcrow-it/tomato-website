<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Cache;
use Cookie;
use Illuminate\Http\Request;
use Storage;

class VideoController extends Controller
{
    public function getKey($id)
    {
        \Debugbar::disable();
    }

    public function oldGetKey($id)
    {
        \Debugbar::disable();
        $key = Cache::get("video-$id");
        if ($key == null) {
            $key = Storage::disk('s3')->get("streaming/$id/secret.key");
        }
        return response($key)
            ->withHeaders([
                'Access-Control-Allow-Origin' => 'http://tomatoonline.edu.vn'
            ])
            ->cookie("video-$id", $key, 1);
    }
}
