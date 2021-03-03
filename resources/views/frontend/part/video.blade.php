@extends('frontend.part.master')

@section('content')
<div class="learningLesson__video">
    <div class="embed-responsive embed-responsive-16by9" id="video-hls-wrapper" style="display: none">
        <video class="embed-responsive-item" id="video-hls" poster="{{ $course->thumbnail }}" controls preload="auto"></video>
    </div>
    <div class="product-detail__img" id="video-js-wrapper" style="display: none">
        <video id="video-js" class="video-js" poster="{{ $course->thumbnail }}" controls preload="auto" data-setup="{}"></video>
    </div>
</div>
@endsection

@section('learning_footer')

@if($course->video_footer_text)
    <div class="card bg-light">
        <div class="card-body">
            {!! $course->video_footer_text !!}
        </div>
    </div>
@endif

<div class="product-detail__detail">
    <div class="tabJs">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tabgioithieu" role="tab" aria-controls="tabgioithieu" aria-selected="true">Giới thiệu</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-tailieu" role="tab" aria-controls="tab-tailieu" aria-selected="false">Tài liệu liên quan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-binhluan" role="tab" aria-controls="tab-giaotrinh" aria-selected="false">Bình luận</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tabgioithieu" role="tabpanel">
                <div class="entry-detail">{!! $course->content !!}</div>
            </div>
            <div class="tab-pane fade" id="tab-tailieu" role="tabpanel">
                <div class="bookBook-retale">
                    <div class="owl-carousel" data-slide-four-item>
                        @foreach($related_books as $item)
                            @include('frontend.category.book_item', [ 'book' => $item ])
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-binhluan" role="tabpanel">
                <div class="commentbox-wrap">
                    <div class="fb-comments" data-href="{{ $course->url }}" data-width="100%" data-numposts="10" data-order-by="reverse_time"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
    $('.entry-detail img').css('height', 'auto');

</script>

<link href="https://vjs.zencdn.net/7.7.6/video-js.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/videojs-hls-quality-selector@1.1.1/dist/videojs-hls-quality-selector.css">
<style>
    .video-js .vjs-big-play-button {
        left: 50%;
        top: 50%;
        transform: translateX(-50%) translateY(-50%);
    }

</style>
<script src="https://vjs.zencdn.net/7.7.6/video.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.15.0/videojs-contrib-hls.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/videojs-contrib-quality-levels@2.0.9/dist/videojs-contrib-quality-levels.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/videojs-hls-quality-selector@1.1.1/dist/videojs-hls-quality-selector.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script>
    var url = '{{ $stream_url }}';

    if (Hls.isSupported()) {
        $('#video-hls-wrapper').show();
        var video = document.getElementById('video-hls');
        var hls = new Hls();
        hls.loadSource(url);
        hls.attachMedia(video);
    } else {
        $('#video-js-wrapper').show();
        var player = videojs('video-js');
        player.src({
            src: url,
            type: 'application/x-mpegURL'
        });
        player.aspectRatio('16:9');
        player.fluid(true);
        player.hlsQualitySelector();
    }

</script>
@endsection
