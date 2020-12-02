@extends('frontend.master')

@section('header')
<title>{{ $part->title }}</title>
<meta content="description" value="{{ $course->meta_description ?? $course->description }}">
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
                            @yield('learning_footer')
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 sticky">
                    <div class="layout-sidebar">
                        <div class="learning-process">
                            <div class="learning-process__header">
                                <h3 class="f-title">Tiến trình học tập</h3>
                            </div>

                            <div class="learning-process__list">
                                <div id="accordion" class="accordionJs">
                                    @foreach($lessons as $lesson)
                                        <div class="panel">
                                            <h3 class="panel__title" data-toggle="collapse" data-target="#collapse-id-{{ $loop->index }}" aria-expanded="true" aria-controls="collapse-id-{{ $loop->index }}">
                                                {{ $lesson->title }}
                                            </h3>
                                            <div id="collapse-id-{{ $loop->index }}" class="collapse show" data-parent="#accordion">
                                                <div class="panel__entry">
                                                    <ul class="collapse__submenu">
                                                        @foreach($lesson->parts as $p)
                                                            <li class="{{ $p->id == $part->id ? 'done current' : '' }}"><a href="{{ $p->url }}"><span></span> {{ $p->title }}</a></li>
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
        scrollTop: $('.learning-process__list .panel .collapse__submenu li.current').position().top - 100
    }, 500);

</script>
@yield('part_script')
@endsection
