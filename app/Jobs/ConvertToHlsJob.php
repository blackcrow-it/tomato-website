<?php

namespace App\Jobs;

use App\CourseVideo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Storage;
use Streaming\FFMpeg;
use Streaming\Format\X264;

class ConvertToHlsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $video;

    public function __construct(CourseVideo $video)
    {
        $this->video = $video;
    }

    public function handle()
    {
        $originalPath = rawurldecode($this->video->original_path);
        if (!$originalPath) return;

        $config = [
            'ffmpeg.binaries'  => config('ffmpeg.ffmpeg.binaries'),
            'ffmpeg.threads'   => config('ffmpeg.ffmpeg.threads'),
            'ffprobe.binaries' => config('ffmpeg.ffprobe.binaries'),
            'timeout'          => config('ffmpeg.timeout'),
        ];

        $log = null;
        if (config('ffmpeg.enable_logging')) {
            $log = new Logger('FFmpeg_Streaming');
            $log->pushHandler(new StreamHandler(storage_path('logs/ffmpeg-streaming.log')));
        }

        $ffmpeg = FFMpeg::create($config, $log);

        $video = $ffmpeg->open(Storage::path("y2mate.com - MONSTAR (ERIK) - 'SAU TẤT CẢ' MV_wHF3Jv6Gk2o_720p.mp4"));

        $format = new X264();
        $format->on('progress', function ($video, $format, $percentage) {
            printf("\rTranscoding...(%s%%) [%s%s]", $percentage, str_repeat('#', $percentage), str_repeat('-', (100 - $percentage)));
        });

        //A path you want to save a random key to your local machine
        $save_to = Storage::path('public/hls/key');
        //A URL (or a path) to access the key on your website
        $url = '/storage/hls/key';
        $video->hls()
            ->encryption($save_to, $url, 1)
            ->setFormat($format)
            ->autoGenerateRepresentations([1080, 720, 360])
            ->save(Storage::path('public/hls/ts/playlist.m3u8'));

        printf("\Completed.");
    }

    public function failed()
    {

    }
}
