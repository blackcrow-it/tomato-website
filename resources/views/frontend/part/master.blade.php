@extends('frontend.master')

@section('header')
<title>{{ $part->title }}</title>
<meta content="description" value="{{ $course->meta_description ?? $course->description }}">
<style>
    .learning-process__list .collapse__submenu li a i {
        position: absolute;
        top: 17px;
        left: 12px;
        width: 10px;
        height: 10px;
    }
</style>
@endsection

@section('body')
<section class="section page-title">
    <div class="container">
        <nav class="breadcrumb-nav">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                @foreach($breadcrumb as $item)
                    <li class="breadcrumb-item"><a href="{{ $item->url }}">{{ $item->title }}</a></li>
                @endforeach
                <li class="breadcrumb-item"><a href="{{ $course->url }}">{{ $course->title }}</a></li>
            </ol>
        </nav>
        <h1 class="page-title__title">{{ $part->title }}</h1>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="layout layout--right learning-lesson-page">
            <div class="row stickyJs fix-header-top">
                <div class="col-xl-9">
                    <div class="layout-content">
                        <div class="learningLesson__header">
                            @yield('content')
                        </div>
                        <div class="learningLesson__footer">
                            @if($is_owned)
                                @yield('learning_footer')
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 sticky">
                    <div class="layout-sidebar">
                        <div class="learning-process">
                            @if(!$is_owned)
                            <div>Bạn chưa sở hữu khóa học này. Mua khoá học <a href="#" onclick="boxBuyCourse()">tại đây</a>.</div><br/>
                            @endif
                            <div class="learning-process__header">
                                <div class="f-title">Tiến trình học tập</div>
                                @if($is_owned)
                                <span class="f-process">{{ $course->getPercentComplete() }}%</span>
                                @endif
                            </div>

                            <div class="learning-process__list">
                                <div id="accordion" class="accordionJs">
                                    @foreach($lessons as $lesson)
                                        <div class="panel">
                                            <div class="panel__title" data-toggle="collapse" data-target="#collapse-id-{{ $loop->index }}" aria-expanded="true" aria-controls="collapse-id-{{ $loop->index }}">
                                                {{ $lesson->title }}
                                                @if($is_owned)
                                                <div class="percent" data-percent="{{ $lesson->getPercentComplete() }}">
                                                    <svg class="percent__svg" width="40" height="40" viewBox="0 0 40 40">
                                                        <circle cx="20" cy="20" r="17" fill="none" stroke="#fd6b7d" stroke-width="3"></circle>
                                                        <circle cx="20" cy="20" r="17" fill="none" stroke="#ffffff" stroke-width="3" class="circle" style="stroke-dasharray: 106.81415022205297px; stroke-dashoffset: 64px;"></circle>
                                                    </svg>
                                                    <span class="percent__number">{{ $lesson->getPercentComplete() }}%</span>
                                                </div>
                                                @endif
                                            </div>
                                            <div id="collapse-id-{{ $loop->index }}" class="collapse show" data-parent="#accordion">
                                                <div class="panel__entry">
                                                    <ul class="collapse__submenu">
                                                        @foreach($lesson->parts as $p)
                                                            @if($is_owned)
                                                                @if($p->is_open)
                                                                <li class="{{ $p->isProcessedWithThisUser() ? 'done' : '' }} {{ $p->id == $part->id ? 'current' : '' }}"><a href="{{ $p->url }}"><span></span> {{ $p->title }}</a></li>
                                                                @else
                                                                <li class="{{ $p->id == $part->id ? 'done current' : '' }}"><a href="#" onclick="boxLockCourse()"><i class="fa fa-lock" aria-hidden="true"></i> {{ $p->title }}</a></li>
                                                                @endif
                                                            @else
                                                                @if($p->enabled_trial)
                                                                <li class="{{ $p->id == $part->id ? 'done current' : '' }}"><a href="{{ $p->url }}"><i class="fa fa-unlock-alt" aria-hidden="true"></i> {{ $p->title }}</a></li>
                                                                @else
                                                                <li class="{{ $p->id == $part->id ? 'done current' : '' }}"><a href="#" onclick="boxBuyCourse()"><i class="fa fa-lock" aria-hidden="true"></i> {{ $p->title }}</a></li>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('footer')
<script>
    $('.learning-process__list').animate({
        scrollTop: $('.learning-process__list .panel .collapse__submenu li.current').offset().top - $('.learning-process__list').offset().top
    }, 500);
    function boxBuyCourse() {
        bootbox.confirm({
            message: '<h1>Thông báo</h1><br/>Hiện tại bạn chưa sở hữu khoá học này.<br/>Ấn <b>"Thanh toán"</b> để mua khoá học.',
            buttons: {
                confirm: {
                    label: 'Thanh toán',
                    className: 'btn--sm btn--success'
                },
                cancel: {
                    label: 'Tiếp tục học thử',
                    className: 'btn--sm bg-dark'
                }
            },
            callback: r => {
                if (!r) return;
                this.submited = true;
                axios.post("{{ route('cart.instant_buy') }}", {
                    course_id: {{ $course->id }}
                })
                .then(function (response) {
                    // location.reload();
                    window.location.href = "{{ route('course', ['slug' => $course->slug, 'id' => $course->id, 'status' => 'success'])}}";
                })
                .catch(function (error) {
                    bootbox.alert(error.errors.course_id[0]);
                });
            }
        });
    }
    @if(!$is_owned)
    boxBuyCourse();
    @endif
    function boxLockCourse() {
        bootbox.alert('<h1>Thông báo!</h1><br/>Bạn chưa hoàn thành bài trắc nghiệm trước đó. Vui lòng hoàn thành để tiếp tục khoá học.');
    }
</script>
@yield('part_script')
@endsection
