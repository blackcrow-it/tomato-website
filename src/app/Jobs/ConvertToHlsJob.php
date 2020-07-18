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
use Str;
use Streaming\FFMpeg;
use Streaming\Format\X264;

class ConvertToHlsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $video;
    private $tmpDir;

    public function __construct(CourseVideo $video)
    {
        $this->video = $video;
        $this->tmpDir = Str::random();

        $this->video->job_progress = 0;
        $this->video->save();
    }

    public function handle()
    {
        $originalPath = rawurldecode($this->video->original_path);
        if (!Storage::disk('s3')->exists($originalPath)) return;

        Storage::deleteDirectory($this->workingPath());

        printf("Download video file.\n");
        $s3 = Storage::disk('s3')->getDriver();
        $local = Storage::getDriver();
        $local->writeStream($this->workingPath('video.tmp'), $s3->readStream($originalPath));

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

        $video = $ffmpeg->open($this->fullWorkingPath('video.tmp'));

        $format = new X264();
        $format->on('progress', function ($video, $format, $percentage) {
            printf("\rTranscoding...(%s%%) [%s%s]", $percentage, str_repeat('#', $percentage), str_repeat('-', (100 - $percentage)));
            $this->video->job_progress = $percentage;
            $this->video->save();
        });

        //A path you want to save a random key to your local machine
        $saveKeyTo = $this->fullWorkingPath('key/secret');
        //A URL (or a path) to access the key on your website
        $keyUrl = route('video.key', [
            'id' => $this->video->id
        ]);

        $video->hls()
            ->encryption($saveKeyTo, $keyUrl)
            ->setFormat($format)
            ->autoGenerateRepresentations([1080, 720, 360])
            ->save($this->fullWorkingPath('ts/playlist.m3u8'));

        printf("\rTrancoded complete.\n");

        printf("Delete origin video.\n");
        Storage::delete($this->workingPath('video.tmp'));

        printf("Delete old hls folder on s3.\n");
        Storage::disk('s3')->deleteDirectory("stream/{$this->video->id}");

        printf("Upload secret key to s3.\n");
        $key = Storage::get($this->workingPath('key/secret'));
        Storage::disk('s3')->put("stream/{$this->video->id}/secret", $key);

        printf("Upload fragments to s3.\n");
        foreach (Storage::allFiles($this->workingPath('ts')) as $path) {
            $content = Storage::get($path);
            Storage::disk('s3')->put("stream/{$this->video->id}/" . basename($path), $content, 'public');
        }

        printf("Delete temporary folder.\n");
        Storage::deleteDirectory($this->workingPath());

        printf("Save info to database.\n");
        $this->video->m3u8_path = "stream/{$this->video->id}/playlist.m3u8";
        $this->video->key_path = "stream/{$this->video->id}/secret";
        $this->video->job_progress = 100;
        $this->video->save();
    }

    public function failed()
    {
        printf("\n");
        sleep(5);
        Storage::deleteDirectory($this->workingPath());
        $this->video->job_progress = -1;
        $this->video->save();
    }

    private function workingPath($path = null)
    {
        return 'tmp/' . $this->tmpDir . ($path ? ('/' . $path) : '');
    }

    private function fullWorkingPath($path = null)
    {
        return Storage::path('tmp/' . $this->tmpDir . ($path ? ('/' . $path) : ''));
    }
}
