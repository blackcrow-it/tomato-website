<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CkFinderDownloader extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ckfinder:enable_custom_endpoint';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apply custom endpoint to ckfinder connector';

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
        $backendFactoryPath = base_path('vendor/ckfinder/ckfinder-laravel-package/_connector/Backend/BackendFactory.php');

        $contents = file_get_contents($backendFactoryPath);

        if (strpos($contents, '$clientConfig[\'endpoint\'] = $backendConfig[\'endpoint\'];') !== false) return;

        $contents = str_replace(
            '$client = new S3Client($clientConfig);',
            '
                if (isset($backendConfig[\'endpoint\'])) {
                    $clientConfig[\'endpoint\'] = $backendConfig[\'endpoint\'];
                }

                $client = new S3Client($clientConfig);
            '
        , $contents);

        file_put_contents($backendFactoryPath, $contents);
    }
}
