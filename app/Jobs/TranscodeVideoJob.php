<?php

namespace App\Jobs;

use App\Constants\TranscodeStatus;
use App\PartVideo;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Storage;
use Streaming\FFMpeg;
use Streaming\Format\X264;
use Streaming\Representation;

class TranscodeVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $partVideo;

    public function __construct(PartVideo $partVideo)
    {
        $this->partVideo = $partVideo;
    }

    public function handle()
    {
        $this->partVideo->transcode_status = TranscodeStatus::PROCESSING;
        $this->partVideo->transcode_message = '0%';
        $this->partVideo->save();

        printf("Create hls folder.\n");
        Storage::deleteDirectory('transcode/hls');
        Storage::makeDirectory('transcode/hls');

        printf("Init transcoding.\n");
        $config = [
            'ffmpeg.binaries'  => config('ffmpeg.ffmpeg.binaries'),
            'ffmpeg.threads'   => config('ffmpeg.ffmpeg.threads'),
            'ffprobe.binaries' => config('ffmpeg.ffprobe.binaries'),
            'timeout'          => config('ffmpeg.timeout'),
        ];

        $log = null;
        if (config('ffmpeg.enable_logging')) {
            $log = new Logger('FFmpeg_Streaming');
            $log->pushHandler(new StreamHandler(storage_path('logs/ffmpeg.log')));
        }

        $ffmpeg = FFMpeg::create($config, $log);
        $video = $ffmpeg->open(Storage::path('transcode/input.tmp'));

        $format = new X264();
        $format->on('progress', function ($video, $format, $percentage) {
            printf("\rTranscoding...(%s%%) [%s%s]", $percentage, str_repeat('#', $percentage), str_repeat('-', (100 - $percentage)));
            $this->partVideo->transcode_message = $percentage . '%';
            $this->partVideo->save();
        });

        $saveKeyTo = Storage::path('transcode/secret.key');
        $keyUrl = route('part_video.get_key', [
            'id' => $this->partVideo->part->id
        ]);

        $r720p  = (new Representation)->setKiloBitrate(1000)->setResize(1280, 720);
        $r1080p  = (new Representation)->setKiloBitrate(2000)->setResize(1920, 1080);

        $video->hls()
            ->encryption($saveKeyTo, $keyUrl)
            ->setFormat($format)
            ->addRepresentations([$r1080p, $r720p])
            ->save(Storage::path('transcode/hls/playlist.m3u8'));

        printf("\rTrancoded complete.\n");

        printf("Delete origin video.\n");
        Storage::delete('transcode/input.tmp');

        $this->partVideo->transcode_message = 'Uploading transcode file';
        $this->partVideo->save();

        printf("Upload secret key to s3.\n");
        $key = Storage::get('transcode/secret.key');
        Storage::disk('s3')->put($this->partVideo->s3_path . '/secret.key', $key);

        printf("Upload hls to s3.\n");
        foreach (Storage::allFiles('transcode/hls') as $path) {
            $content = Storage::get($path);
            Storage::disk('s3')->put($this->partVideo->s3_path . '/hls/' . basename($path), $content, 'public');
        }

        printf("Clear transcode folder.\n");
        Storage::deleteDirectory('transcode');

        $this->partVideo->transcode_status = TranscodeStatus::COMPLETED;
        $this->partVideo->transcode_message = null;
        $this->partVideo->save();
    }

    public function failed(Exception $ex)
    {
        $this->partVideo->transcode_status = TranscodeStatus::FAIL;
        $this->partVideo->transcode_message = $ex->getMessage();
        $this->partVideo->save();
    }
}
