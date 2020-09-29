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
        if ($this->partVideo->transcode_status != TranscodeStatus::PENDING) return;

        $this->partVideo->transcode_status = TranscodeStatus::PROCESSING;
        $this->partVideo->save();

        try {
            $s3Dir = pathinfo($this->partVideo->s3_path, PATHINFO_DIRNAME);

            printf("Clear transcode folder.\n");
            Storage::deleteDirectory('transcode');

            printf("Download video file.\n");
            $s3Driver = Storage::disk('s3')->getDriver();
            $localDriver = Storage::getDriver();
            $localDriver->writeStream('transcode/input.tmp', $s3Driver->readStream($this->partVideo->s3_path));
            gc_collect_cycles();

            printf("Delete folder on s3.\n");
            Storage::disk('s3')->deleteDirectory($s3Dir);

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
                'id' => $this->partVideo->id
            ]);

            $video->hls()
                ->encryption($saveKeyTo, $keyUrl)
                ->setFormat($format)
                ->autoGenerateRepresentations([1080, 720])
                ->save(Storage::path('transcode/hls/playlist.m3u8'));

            printf("\rTrancoded complete.\n");

            printf("Delete origin video.\n");
            Storage::delete('transcode/input.tmp');

            printf("Upload secret key to s3.\n");
            $key = Storage::get('transcode/secret.key');
            Storage::disk('s3')->put($s3Dir . '/secret.key', $key);

            printf("Upload hls to s3.\n");
            foreach (Storage::allFiles('transcode/hls') as $path) {
                $content = Storage::get($path);
                Storage::disk('s3')->put($s3Dir . '/' . basename($path), $content, 'public');
            }

            printf("Clear transcode folder.\n");
            Storage::deleteDirectory('transcode');

            $this->partVideo->s3_path = $s3Dir;
            $this->partVideo->transcode_status = TranscodeStatus::COMPLETED;
            $this->partVideo->transcode_message = null;
            $this->partVideo->save();
        } catch (Exception $ex) {
            $this->partVideo->transcode_status = TranscodeStatus::FAIL;
            $this->partVideo->transcode_message = $ex->getMessage();
            $this->partVideo->save();
        }
    }
}
