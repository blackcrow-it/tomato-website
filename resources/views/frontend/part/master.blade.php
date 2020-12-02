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
