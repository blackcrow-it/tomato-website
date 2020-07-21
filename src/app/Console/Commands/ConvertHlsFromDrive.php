<?php

namespace App\Console\Commands;

use DB;
use Google_Client;
use Google_Service_Drive;
use Illuminate\Console\Command;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Storage;
use Streaming\FFMpeg;
use Streaming\Format\X264;

class ConvertHlsFromDrive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:drive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        printf("Init google drive connect.\n");

        $googleClientId = DB::connection('mysql')->table('setting')->where('setting_name', 'google_client_id')->first()->setting_value;
        $googleClientSecret = DB::connection('mysql')->table('setting')->where('setting_name', 'google_client_secret')->first()->setting_value;
        $refreshToken = DB::connection('mysql')->table('setting')->where('setting_name', 'google_refresh_token')->first()->setting_value;

        $client = new Google_Client();
        $client->setClientId($googleClientId);
        $client->setClientSecret($googleClientSecret);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $client->setIncludeGrantedScopes(true);
        $client->addScope(Google_Service_Drive::DRIVE);
        $client->fetchAccessTokenWithRefreshToken($refreshToken);

        $drive = new Google_Service_Drive($client);

        printf("Init ffmpeg config.\n");

        $ffmpegConfig = [
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

        $ffmpeg = FFMpeg::create($ffmpegConfig, $log);

        printf("Get videos data.\n");

        $videos = DB::connection('mysql')->table('video')->where('drive_id', '!=', '')->whereNull('stream_url')->get();

        foreach ($videos as $video) {
            $client->fetchAccessTokenWithRefreshToken($refreshToken);

            $mp4Path = 'transcode/' . $video->video_id . '.mp4';
            $keyPath = 'transcode/secret.key';
            $m3u8Path = 'transcode/hls/playlist.m3u8';

            printf("Delete transcode directory.\n");

            Storage::deleteDirectory('transcode');

            Storage::makeDirectory('transcode');

            printf("Download video : " . $video->title . ".\n");

            $file = $drive->files->get($video->drive_id, [
                'fields' => 'id, size'
            ]);

            // Get the authorized Guzzle HTTP client
            $http = $client->authorize();

            // Open a file for writing
            $fp = fopen(Storage::path($mp4Path), 'w');

            // Download in 10 MB chunks
            $chunkSizeBytes = 10 * 1024 * 1024;
            $chunkStart = 0;

            // Iterate over each chunk and write it to our file
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

            printf("Start transcode.\n");

            $videoHandle = $ffmpeg->open(Storage::path($mp4Path));

            $format = new X264();
            $format->on('progress', function ($video, $format, $percentage) {
                printf("\rTranscoding...(%s%%) [%s%s]", $percentage, str_repeat('#', $percentage), str_repeat('-', (100 - $percentage)));
            });

            //A path you want to save a random key to your local machine
            $saveKeyTo = Storage::path($keyPath);
            //A URL (or a path) to access the key on your website
            $keyUrl = route('video.old_key', ['id' => $video->video_id]);

            $videoHandle->hls()
                ->encryption($saveKeyTo, $keyUrl)
                ->setFormat($format)
                ->autoGenerateRepresentations([1080, 720, 360])
                ->save(Storage::path($m3u8Path));

            printf("\r\nTrancoded complete.\n");

            printf("Start upload to s3.\n");

            Storage::disk('s3')->deleteDirectory('streaming/' . $video->video_id);
            $this->uploadFileToS3('transcode/secret.key', 'streaming/' . $video->video_id . '/secret.key');
            foreach (Storage::files('transcode/hls') as $path) {
                $this->uploadFileToS3($path, 'streaming/' . $video->video_id . '/hls/' . basename($path), true);
            }

            printf("Update stream url to database.\n");

            DB::connection('mysql')->table('video')->where('video_id', $video->video_id)->update([
                'stream_url' => Storage::disk('s3')->url('streaming/' . $video->video_id . '/hls/playlist.m3u8')
            ]);

            printf("Delete transcode directory.\n");

            Storage::deleteDirectory('transcode');
        }
    }

    private function uploadFileToS3($localPath, $s3Path, $public = false)
    {
        $contents = Storage::get($localPath);
        Storage::disk('s3')->put($s3Path, $contents, $public ? 'public' : 'private');
    }
}
