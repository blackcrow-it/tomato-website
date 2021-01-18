<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Storage;

class ChangeHlsDomain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hls:change_domain';

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
        $dirs = Storage::disk('s3')->directories('part_video');
        foreach ($dirs as $dir) {
            $contents = Storage::disk('s3')->get($dir . 'hls/playlist_1080p.m3u8');
            $contents = str_replace('http://serve.tomatoonline.edu.vn/', 'https://tomatoonline.edu.vn/', $contents);
            Storage::disk('s3')->put($dir . 'hls/playlist_1080p.m3u8', $contents);

            $contents = Storage::disk('s3')->get($dir . 'hls/playlist_720p.m3u8');
            $contents = str_replace('http://serve.tomatoonline.edu.vn/', 'https://tomatoonline.edu.vn/', $contents);
            Storage::disk('s3')->put($dir . 'hls/playlist_720p.m3u8', $contents);
        }
    }
}
