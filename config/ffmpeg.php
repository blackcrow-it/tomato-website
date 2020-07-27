<?php

return [
    'ffmpeg' => [
        'binaries' => env('FFMPEG_BINARIES', 'ffmpeg'),
        'threads'  => 8,
    ],

    'ffprobe' => [
        'binaries' => env('FFPROBE_BINARIES', 'ffprobe'),
    ],

    'timeout' => 3600,

    'enable_logging' => (bool) env('APP_DEBUG', false),

    'using_cuda' => (bool) env('USING_CUDA', false),
];
