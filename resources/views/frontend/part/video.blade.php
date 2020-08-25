@extends('frontend.part.master')

@section('content')
<link href="https://vjs.zencdn.net/7.7.6/video-js.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/videojs-hls-quality-selector@1.1.1/dist/videojs-hls-quality-selector.css">
<style>
    .video-js .vjs-big-play-button {
        left: 50%;
        top: 50%;
        transform: translateX(-50%) translateY(-50%);
    }

</style>

<div class="learningLesson__video">
    <div class="product-detail__img">
        <video id="video" class="video-js" controls preload="auto" data-setup="{}"></video>
    </div>
</div>

<script src="https://vjs.zencdn.net/7.7.6/video.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.15.0/videojs-contrib-hls.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/videojs-contrib-quality-levels@2.0.9/dist/videojs-contrib-quality-levels.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/videojs-hls-quality-selector@1.1.1/dist/videojs-hls-quality-selector.min.js"></script>

<script>
    var player = videojs('video');
    player.src({
        src: '{{ $stream_url }}',
        type: 'application/x-mpegURL'
    });
    player.aspectRatio('16:9');
    player.fluid(true);
    player.hlsQualitySelector();

</script>
@endsection
