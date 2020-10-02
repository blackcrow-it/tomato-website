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
use Storage;

class TranscodeFromS3Job implements ShouldQueue
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
        $this->partVideo->transcode_message = 'Downloading video';
        $this->partVideo->save();

        printf("Clear transcode folder.\n");
        Storage::deleteDirectory('transcode');

        printf("Download video file.\n");
        $s3Driver = Storage::disk('s3')->getDriver();
        $localDriver = Storage::getDriver();
        $localDriver->writeStream('transcode/input.tmp', $s3Driver->readStream($this->partVideo->s3_path . '/input.tmp'));
        gc_collect_cycles();

        printf("Delete folder on s3.\n");
        Storage::disk('s3')->deleteDirectory($this->partVideo->s3_path);

        TranscodeVideoJob::dispatchNow($this->partVideo);
    }

    public function failed(Exception $ex)
    {
        $this->partVideo->transcode_status = TranscodeStatus::FAIL;
        $this->partVideo->transcode_message = $ex->getMessage();
        $this->partVideo->save();
    }
}
