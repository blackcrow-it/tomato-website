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
use Str;

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
        $this->partVideo->transcode_status = TranscodeStatus::PROCESSING;
        $this->partVideo->transcode_message = 'Downloading video';

        do {
            $this->partVideo->transcode_dir = 'transcode_' . now()->format('YmdHis') . '_' . Str::random(6);
            sleep(1);
        } while (Storage::exists($this->partVideo->transcode_dir));
        Storage::makeDirectory($this->partVideo->transcode_dir);

        $this->partVideo->save();

        printf("Download video file.\n");
        $s3Driver = Storage::disk('s3')->getDriver();
        $localDriver = Storage::getDriver();
        $localDriver->writeStream($this->partVideo->transcode_dir . '/input.tmp', $s3Driver->readStream($this->partVideo->s3_path . '/input.tmp'));
        gc_collect_cycles();

        TranscodeVideoJob::dispatchNow($this->partVideo);

        printf("Delete file on s3.\n");
        $this->partVideo->refresh();
        if ($this->partVideo->transcode_status == TranscodeStatus::COMPLETED) {
            Storage::disk('s3')->delete($this->partVideo->s3_path . '/input.tmp');
        }

        Storage::deleteDirectory($this->partVideo->transcode_dir);
    }

    public function failed(Exception $ex)
    {
        $this->partVideo->refresh();
        $this->partVideo->transcode_status = TranscodeStatus::FAIL;
        $this->partVideo->transcode_message = $ex->getMessage();
        $this->partVideo->save();

        Storage::deleteDirectory($this->partVideo->transcode_dir);
    }
}
