<?php

namespace App\Jobs;

use App\CourseVideo;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Storage;

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
        $video = FFMpeg::fromDisk('s3')->open($originalPath);

        $lowBitrate = (new X264())->setAudioCodec('libmp3lame')->setKiloBitrate(250);
        $midBitrate = (new X264())->setAudioCodec('libmp3lame')->setKiloBitrate(500);
        $highBitrate = (new X264())->setAudioCodec('libmp3lame')->setKiloBitrate(1000);

        $video->exportForHLS()
            ->onProgress(function ($percentage) {
                echo "$percentage% transcoded.\n";
            })
            ->setSegmentLength(5)
            ->setKeyFrameInterval(48)
            ->addFormat($lowBitrate)
            ->addFormat($midBitrate)
            ->addFormat($highBitrate)
            ->toDisk('s3')
            ->withVisibility('public')
            ->save('hls/' . $this->video->course_id . '/playlist.m3u8');
    }

    public function failed()
    {
        Storage::disk('s3')->deleteDirectory('hls/' . $this->video->course_id);
    }
}
