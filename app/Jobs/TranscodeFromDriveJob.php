<?php

namespace App\Jobs;

use App\Constants\TranscodeStatus;
use App\PartVideo;
use Exception;
use Google_Client;
use Google_Service_Drive;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Storage;
use Str;

class TranscodeFromDriveJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $partVideo;
    private $driveFileId;

    public function __construct(PartVideo $partVideo, $driveFileId)
    {
        $this->partVideo = $partVideo;
        $this->driveFileId = $driveFileId;
    }

    public function handle()
    {
        if ($this->partVideo->transcode_status != TranscodeStatus::PENDING) return;

        $this->partVideo->transcode_status = TranscodeStatus::PROCESSING;
        $this->partVideo->transcode_message = 'Downloading video';

        do {
            $this->partVideo->transcode_dir = 'transcode_' . now()->format('YmdHis') . '_' . Str::random(6);
            sleep(1);
        } while (Storage::exists($this->partVideo->transcode_dir));
        Storage::makeDirectory($this->partVideo->transcode_dir);

        $this->partVideo->save();

        printf("Download video file.\n");

        $client = new Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->fetchAccessTokenWithRefreshToken(config('settings.google_drive_refresh_token'));

        $drive = new Google_Service_Drive($client);

        $file = $drive->files->get($this->driveFileId, [
            'fields' => 'id, size'
        ]);

        // Download in 10 MB chunks
        $chunkSizeBytes = 10 * 1024 * 1024;
        $chunkStart = 0;

        // Create file pointer
        $fp = fopen(Storage::path($this->partVideo->transcode_dir . '/input.tmp'), 'w');

        // Iterate over each chunk and write it to our file
        $http = $client->authorize();
        while ($chunkStart < $file->getSize()) {
            $chunkEnd = $chunkStart + $chunkSizeBytes;
            $response = $http->request(
                'GET',
                sprintf('/drive/v3/files/%s', $file->getId()),
                [
                    'query' => ['alt' => 'media'],
                    'headers' => [
                        'Range' => sprintf('bytes=%s-%s', $chunkStart, $chunkEnd)
                    ]
                ]
            );
            $chunkStart = $chunkEnd + 1;
            fwrite($fp, $response->getBody()->getContents());
        }

        // close the file pointer
        fclose($fp);
        gc_collect_cycles();

        TranscodeVideoJob::dispatchNow($this->partVideo);

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
