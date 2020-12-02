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

@section('learning_footer')
<div class="product-detail__detail">
    <div class="tabJs">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tabgioithieu" role="tab" aria-controls="tabgioithieu" aria-selected="true">Giới thiệu</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-giangvien" role="tab" aria-controls="tab-giangvien" aria-selected="false">Giảng viên</a>
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
            <div class="tab-pane fade" id="tab-giangvien" role="tabpanel">
                @if($course->teacher)
                    <div class="product-detail__team">
                        <div class="row">
                            <div class="col-md-4 col-xl-4">
                                <div class="f-avatar">
                                    <img src="{{ $course->teacher->avatar }}">
                                </div>
                            </div>
                            <div class="col-md-8 col-xl-8">
                                <div class="f-content">
                                    <div class="entry-detail">
                                        <h3>{{ $course->teacher->name }}</h3>
                                        {!! $course->teacher->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="tab-pane fade" id="tab-tailieu" role="tabpanel">
                <div class="bookBook-retale">
                    <div class="owl-carousel" data-slide-four-item>
                        <div class="bookBox">
                            <a href="chitietsach.html" class="bookBox__img">
                                <img src="assets/img/image/bookBox-1.jpg" alt="">
                                <span class="sale">-50%</span>
                            </a>
                            <div class="bookBox__body">
                                <h3 class="bookBok__title"><a href="chitietsach.html">Giáo trình hán ngữ</a></h3>
                                <div class="bookBok__price">
                                    <ins>499.000đ</ins>
                                    <del>899.000đ</del>
                                </div>
                            </div>
                            <div class="bookBox__btn">
                                <a href="#" class="btn btn--secondary btn--sm btn-buy-and">Mua kèm</a>
                            </div>
                        </div>
                        <div class="bookBox">
                            <a href="chitietsach.html" class="bookBox__img">
                                <img src="assets/img/image/bookBox-1.jpg" alt="">
                                <span class="sale">-50%</span>
                            </a>
                            <div class="bookBox__body">
                                <h3 class="bookBok__title"><a href="chitietsach.html">Giáo trình hán ngữ</a></h3>
                                <div class="bookBok__price">
                                    <ins>499.000đ</ins>
                                    <del>899.000đ</del>
                                </div>
                            </div>
                            <div class="bookBox__btn">
                                <a href="#" class="btn btn--secondary btn--sm btn-buy-and">Mua kèm</a>
                            </div>
                        </div>
                        <div class="bookBox">
                            <a href="chitietsach.html" class="bookBox__img">
                                <img src="assets/img/image/bookBox-1.jpg" alt="">
                                <span class="sale">-50%</span>
                            </a>
                            <div class="bookBox__body">
                                <h3 class="bookBok__title"><a href="chitietsach.html">Giáo trình hán ngữ</a></h3>
                                <div class="bookBok__price">
                                    <ins>499.000đ</ins>
                                    <del>899.000đ</del>
                                </div>
                            </div>
                            <div class="bookBox__btn">
                                <a href="#" class="btn btn--secondary btn--sm btn-buy-and">Mua kèm</a>
                            </div>
                        </div>
                        <div class="bookBox">
                            <a href="chitietsach.html" class="bookBox__img">
                                <img src="assets/img/image/bookBox-1.jpg" alt="">
                                <span class="sale">-50%</span>
                            </a>
                            <div class="bookBox__body">
                                <h3 class="bookBok__title"><a href="chitietsach.html">Giáo trình hán ngữ</a></h3>
                                <div class="bookBok__price">
                                    <ins>499.000đ</ins>
                                    <del>899.000đ</del>
                                </div>
                            </div>
                            <div class="bookBox__btn">
                                <a href="#" class="btn btn--secondary btn--sm btn-buy-and">Mua kèm</a>
                            </div>
                        </div>
                        <div class="bookBox">
                            <a href="chitietsach.html" class="bookBox__img">
                                <img src="assets/img/image/bookBox-1.jpg" alt="">
                                <span class="sale">-50%</span>
                            </a>
                            <div class="bookBox__body">
                                <h3 class="bookBok__title"><a href="chitietsach.html">Giáo trình hán ngữ</a></h3>
                                <div class="bookBok__price">
                                    <ins>499.000đ</ins>
                                    <del>899.000đ</del>
                                </div>
                            </div>
                            <div class="bookBox__btn">
                                <a href="#" class="btn btn--secondary btn--sm btn-buy-and">Mua kèm</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-binhluan" role="tabpanel">
                <div class="commentbox-wrap">
                    <div class="fb-comments" data-href="{{ $course->url }}" data-width="100%" data-numposts="10"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
