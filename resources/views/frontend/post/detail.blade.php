@extends('frontend.master')

@section('header')
<title>{{ $post->meta_title ?? $post->title }}</title>
<meta content="description" value="{{ $post->meta_description ?? $post->description }}">
<meta property="og:title" content="{{ $post->og_title ?? $post->meta_title ?? $post->title }}">
<meta property="og:description" content="{{ $post->og_description ?? $post->meta_description ?? $post->description }}">
<meta property="og:url" content="{{ $post->url }}">
<meta property="og:image" content="{{ $post->og_image ?? $post->cover }}">
<meta property="og:type" content="website">
<link rel="canonical" href="{{ $post->url }}">
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
            </ol>
        </nav>
        <h1 class="page-title__title">{{ $post->title }}</h1>
    </div>
</section>

<section class="section pb-0">
    <div class="container">
        <div class="layout layout--right">
            <div class="row">
                <div class="col-xl-9 order-xl-2">
                    <div class="layout-content">
                        <div class="post-detail detailbox">
                            @if($post->cover)
                                <div class="post-detail__img">
                                    <img src="{{ $post->cover }}" alt="{{ $post->title }}">
                                </div>
                            @endif
                            <div class="detailbox__content entry-detail">{!! $post->content !!}</div>

                            <div class="post-detail__navigation">
                                <div class="f-item first">
                                    @if($prev_post)
                                        <div class="f-item__inner">
                                            <a href="{{ $prev_post->url }}">
                                                <h3>{{ $prev_post->title }}</h3>
                                                <span><i class="fa fa-long-arrow-left"></i> Bài trước</span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="f-item last">
                                    @if($next_post)
                                        <div class="f-item__inner">
                                            <a href="{{ $next_post->url }}">
                                                <h3>{{ $next_post->title }}</h3>
                                                <span>Bài tiếp theo <i class="fa fa-long-arrow-right"></i></span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="post-detail__relatedCourse">
                            <h2 class="title-page-min">Khoá học liên quan</h2>
                            <div class="owl-carousel fixheight lessonbox-wrap-min">
                                @foreach($related_courses as $item)
                                    @include('frontend.category.course_item', [ 'course' => $item ])
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 order-xl-1">
                    <div class="layout-sidebar">
                        <div class="widget widget--relatePost">
                            <h2 class="widget__title">Bài viết liên quan</h2>

                            <ul>
                                @foreach($related_posts as $item)
                                    <li>
                                        <a href="{{ $item->url }}">
                                            <img src="{{ $item->thumbnail }}">
                                            <h3>{{ $item->title }}</h3>
                                            <span><i class="fa fa-clock-o"></i>{{ $item->updated_at->format('d/m/Y') }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="widget widget--course">
                            <h2 class="widget__title">Khoá học</h2>

                            <ul>
                                @foreach(get_categories(null, 'course-categories') as $c1)
                                    <li class="item">
                                        <a href="{{ $c1->url }}">
                                            <span class="item__icon">
                                                <img src="{{ $c1->icon }}" alt="{{ $c1->title }}">
                                            </span>
                                            <h3 class="item__title">{{ $c1->title }}</h3>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="widget widget--book">
                            <h2 class="widget__title">Sách liên quan</h2>
                            <div class="owl-carousel">
                                <a href="chitietsach.html" class="item">
                                    <div class="item__img"><img src="assets/img/image/bookBox-1.jpg"></div>
                                    <h3 class="item__title">Giáo trình hán ngữ</h3>
                                    <div class="item__price">
                                        <ins>499.000đ</ins>
                                        <del>899.000đ</del>
                                    </div>
                                </a>
                                <a href="chitietsach.html" class="item">
                                    <div class="item__img"><img src="assets/img/image/bookBox-1.jpg"></div>
                                    <h3 class="item__title">Giáo trình hán ngữ</h3>
                                    <div class="item__price">
                                        <ins>499.000đ</ins>
                                        <del>899.000đ</del>
                                    </div>
                                </a>
                                <a href="chitietsach.html" class="item">
                                    <div class="item__img"><img src="assets/img/image/bookBox-1.jpg"></div>
                                    <h3 class="item__title">Giáo trình hán ngữ</h3>
                                    <div class="item__price">
                                        <ins>499.000đ</ins>
                                        <del>899.000đ</del>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section pt-0">
    <div class="container">
        <div class="row">
            <div class="col-xl-9 offset-xl-3">
                <div class="commentbox-wrap">
                    <h3 class="title-page-min">Bình luận bài viết</h3>
                    <div class="fb-comments" data-href="{{ $post->url }}" data-width="100%" data-numposts="10"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
