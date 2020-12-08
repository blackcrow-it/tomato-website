<?php

namespace App\Console\Commands;

use DB;
use Exception;
use File;
use Google_Client;
use Google_Service_Drive;
use Illuminate\Console\Command;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Storage;
use Streaming\FFMpeg;
use Streaming\Format\HEVC;
use Streaming\Format\X264;
use Streaming\Representation;

class OldVideoTranscode extends Command
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

        $googleClientId = DB::connection('old_db')->table('setting')->where('setting_name', 'google_client_id')->first()->setting_value;
        $googleClientSecret = DB::connection('old_db')->table('setting')->where('setting_name', 'google_client_secret')->first()->setting_value;
        $refreshToken = DB::connection('old_db')->table('setting')->where('setting_name', 'google_refresh_token')->first()->setting_value;

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

        $usingCuda = config('ffmpeg.using_cuda');

        $log = null;
        if (config('ffmpeg.enable_logging')) {
            $log = new Logger('FFmpeg_Streaming');
            $log->pushHandler(new StreamHandler(storage_path('logs/ffmpeg-streaming.log')));
        }

        $ffmpeg = FFMpeg::create($ffmpegConfig, $log);

        printf("Get videos data.\n");

        while (true) {
            $video = DB::connection('old_db')
                ->table('video')
                ->where('drive_id', '!=', '')
                ->whereNull('stream_url')
                // ->orderBy('video_id', 'desc')
                ->first();

            if ($video == null) break;

            try {
                printf("Video: " . $video->title . ".\n");

                printf("Set current trancode.\n");

                DB::connection('old_db')->table('video')->where('video_id', $video->video_id)->update([
                    'stream_url' => 0
                ]);

                $client->fetchAccessTokenWithRefreshToken($refreshToken);

                $mp4Path = 'transcode/' . $video->video_id . '/video.mp4';
                $keyPath = 'transcode/' . $video->video_id . '/secret.key';
                $m3u8Path = 'transcode/' . $video->video_id . '/hls/playlist.m3u8';

                printf("Download video.\n");

                $file = $drive->files->get($video->drive_id, [
                    'fields' => 'id, size'
                ]);

                if (!Storage::exists($mp4Path) || Storage::size($mp4Path) != $file->getSize()) {
                    printf("Re-create transcode directory.\n");
                    Storage::deleteDirectory('transcode/' . $video->video_id);
                    Storage::makeDirectory('transcode/' . $video->video_id);

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

                        $percentage = floor($chunkStart / $file->getSize() * 100);
                        printf("\rDownloading...(%s/%sMB) [%s%s]", round($chunkStart / 1024 / 1024), round($file->getSize() / 1024 / 1024), str_repeat('#', $percentage), str_repeat('-', (100 - $percentage)));
                    }
                    printf("\r\nDownload complete.\n");

                    // close the file pointer
                    fclose($fp);
                }

                printf("Start transcode.\n");

                Storage::deleteDirectory('transcode/' . $video->video_id . '/hls');
                Storage::makeDirectory('transcode/' . $video->video_id . '/hls');

                $videoHandle = $ffmpeg->customInput(Storage::path($mp4Path), $usingCuda ? ['-hwaccel', 'cuda'] : []);

                $format = new X264();
                $format->on('progress', function ($video, $format, $percentage) {
                    printf("\rTranscoding...(%s%%) [%s%s]", $percentage, str_repeat('#', $percentage), str_repeat('-', (100 - $percentage)));
                });

                //A path you want to save a random key to your local machine
                $saveKeyTo = Storage::path($keyPath);
                //A URL (or a path) to access the key on your website
                $keyUrl = route('video.old_key', ['id' => $video->video_id]);

                $r720p  = (new Representation)->setKiloBitrate(1000)->setResize(1280, 720);
                $r1080p  = (new Representation)->setKiloBitrate(2000)->setResize(1920, 1080);

                $videoHandle
                    ->hls()
                    ->encryption($saveKeyTo, $keyUrl)
                    ->setFormat($format)
                    ->addRepresentations([$r1080p, $r720p])
                    ->save(Storage::path($m3u8Path));

                printf("\r\nTrancoded complete.\n");

                printf("Start upload to s3.\n");

                while (true) {
                    Storage::disk('s3')->deleteDirectory('streaming/' . $video->video_id);

                    try {
                        printf("Transferring secret key.\n");
                        $this->uploadFileToS3('transcode/' . $video->video_id . '/secret.key', 'streaming/' . $video->video_id . '/secret.key');
                        $this->uploadDirectoryToS3('transcode/' . $video->video_id . '/hls/', 'streaming/' . $video->video_id . '/hls/', true);

                        break;
                    } catch (Exception $ex) {
                        printf("[ERROR] " . $ex->getMessage() . "\nRe-upload after 5s...\n");
                        sleep(5);
                    }
                }

                printf("Update stream url to database.\n");

                DB::connection('old_db')->table('video')->where('video_id', $video->video_id)->update([
                    'stream_url' => Storage::disk('s3')->url('streaming/' . $video->video_id . '/hls/playlist.m3u8')
                ]);

                printf("Delete transcode directory.\n");

                Storage::deleteDirectory('transcode/' . $video->video_id);
            } catch (\Throwable $th) {
                printf("Exception, revert current trancode.\n");

                DB::connection('old_db')->table('video')->where('video_id', $video->video_id)->update([
                    'stream_url' => null
                ]);
            }
        }
    }

    private function uploadFileToS3($localPath, $s3Path, $public = false)
    {
        $stream = Storage::getDriver()->readStream($localPath);
        Storage::disk('s3')->writeStream($s3Path, $stream, [
            'visibility' => $public ? 'public' : 'private'
        ]);
        if (is_resource($stream)) fclose($stream);
        gc_collect_cycles();
    }

    private function uploadDirectoryToS3($localPath, $s3Path, $public = false)
    {
        $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
        $dir = Storage::path($localPath);
        $bucket = config('filesystems.disks.s3.bucket');
        $keyPrefix = $s3Path;
        $options = array(
            'concurrency' => 20,
            'debug' => true,
            'before' => function (\Aws\Command $command) use ($public) {
                $command['ACL'] = $public ? 'public-read' : 'private';
            }
        );
        $client->uploadDirectory($dir, $bucket, $keyPrefix, $options);
        gc_collect_cycles();
    }
}
