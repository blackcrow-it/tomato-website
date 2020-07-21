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

        $video = DB::connection('mysql')->table('video')->where('drive_id', '!=', '')->first();
        $response = $drive->files->get($video->drive_id, [
            'alt' => 'media'
        ]);

        $soragePath = 'transcode/' . $video->video_id . '.mp4';
        $keyPath = 'transcode/key/secret';
        Storage::put($soragePath, $response->getBody());

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

        $video = $ffmpeg->open(Storage::path($soragePath));

        $format = new X264();
        $format->on('progress', function ($video, $format, $percentage) {
            printf("\rTranscoding...(%s%%) [%s%s]", $percentage, str_repeat('#', $percentage), str_repeat('-', (100 - $percentage)));
        });

        //A path you want to save a random key to your local machine
        $saveKeyTo = Storage::path($keyPath);
        //A URL (or a path) to access the key on your website
        $keyUrl = route('video.key', [
            'id' => $this->video->id
        ]);

        $video->hls()
            ->encryption($saveKeyTo, $keyUrl)
            ->setFormat($format)
            ->autoGenerateRepresentations([1080, 720, 360])
            ->save($this->fullWorkingPath('ts/playlist.m3u8'));
    }
}
