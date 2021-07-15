@extends('frontend.part.master')

@section('content')
<div class="learningLesson__video">
    <div class="product-detail__img">
        <div class="embed-responsive embed-responsive-16by9">
            {{-- <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{ $data->youtube_id ?? null }}" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> --}}
            <div class="embed-responsive-item" id="player"></div>
        </div>
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
    <script>
        var done = false;
        var tag = document.createElement('script');

        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        var player;
        function onYouTubeIframeAPIReady() {
          player = new YT.Player('player', {
            height: '390',
            width: '640',
            videoId: '{{ $data->youtube_id ?? null }}',
            playerVars: {
              'playsinline': 1
            },
            events: {
              'onReady': onPlayerReady,
              'onStateChange': onPlayerStateChange,
            }
          });
        }

        function onPlayerReady(event) {
            event.target.getDuration();
        }
        function onPlayerStateChange(event) {
            if (!done) {
                checkComplete()
            }
        }
        $(window).click(function(e) {
            if (!done) {
                checkComplete()
            }
        });
        function checkComplete() {
            if (player.getCurrentTime() >= player.getDuration()/100*80) {
                done = true;
                axios.post("{{ route('part.set_complete') }}", { part_id: {{$part->id}} })
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                })
            }
        }
      </script>
@endsection
