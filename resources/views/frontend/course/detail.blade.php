@extends('frontend.master')

@section('header')
<title>{{ $course->meta_title ?? $course->title }}</title>
<meta content="description" value="{{ $course->meta_description ?? $course->description }}">
<meta property="og:title" content="{{ $course->og_title ?? $course->meta_title ?? $course->title }}">
<meta property="og:description" content="{{ $course->og_description ?? $course->meta_description ?? $course->description }}">
<meta property="og:url" content="{{ $course->url }}">
<meta property="og:image" content="{{ $course->og_image ?? $course->cover }}">
<meta property="og:type" content="website">
<link rel="canonical" href="{{ $course->url }}">
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
        <h1 class="page-title__title">{{ $course->title }}</h1>
    </div>
</section>

<section class="section">
    <div class="container">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $msg)
                        <li>{{ $msg }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="product-detail">
            <div class="row">
                <div class="col-md-6 col-xl-7">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{ $course->intro_youtube_id ?? null }}" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col-md-6 col-xl-5">
                    <div class="product-detail__price">
                        <div>
                            <ins>{{ currency($course->price) }}</ins>
                            @if($course->original_price)
                                <del>{{ currency($course->original_price) }}</del>
                            @endif
                        </div>

                        @if($course->original_price)
                            <span class="sale">-{{ ceil(100 - $course->price / $course->original_price * 100) }}%</span>
                        @endif
                    </div>

                    <ul class="product-detail__meta">
                        <li>Trình độ: Mới bắt đầu</li>
                        <li>Bài học: 15 bài</li>
                        <li>Giảng viên: Bùi Thu Hà</li>
                        <li>Học viên tham gia: 300</li>
                        <li>Kiến thức nền tảng cho việc học Hán ngữ</li>
                        <li>Nắm được ngữ âm cơ bản và sử dụng thành thạo cách đọc, ghi phiên âm</li>
                        <li>Hỗ trợ Link download giáo trình và giải đáp các câu hỏi kiến thức trực tiếp từ giảng viên</li>
                        <li>Sở hữu mãi mãi</li>
                    </ul>

                    @if(auth()->check())
                        @if($is_owned)
                            <a href="{{ route('course.start', [ 'id' => $course->id ]) }}" class="btn">Xem bài giảng</a>
                        @else
                            <div class="product-detal__btn">
                                <div class="btn-wrap">
                                    <button type="button" data-form="#add-to-cart" data-redirect="{{ route('cart.confirm') }}" class="btn btn-buy-now">Mua ngay</button>
                                    <button type="button" data-form="#add-to-cart" class="btn btn--secondary btn-add-to-cart {{ $added_to_cart ? 'added' : '' }}">
                                        <span class="add-to-cart-text">Thêm vào giỏ</span>
                                        <span class="loading-text"><i class="fa fa-opencart"></i> Đang thêm...</span>
                                        <span class="complete-text"><i class="fa fa-check"></i> Đã thêm</span>
                                    </button>
                                </div>
                                <div class="btn-min">hoặc <a href="#consultationForm" class="btn-scroll-form">Đăng ký nhận tư vấn</a></div>
                            </div>
                            @if(!$added_to_cart)
                                <form action="{{ route('cart.add') }}" id="add-to-cart" class="invisible">
                                    <input type="hidden" name="object_id" value="{{ $course->id }}">
                                    <input type="hidden" name="type" value="{{ \App\Constants\ObjectType::COURSE }}">
                                </form>
                            @endif
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn">Đăng nhập để tiếp tục</a>
                        <div class="product-detal__btn">
                            <div class="btn-min">hoặc <a href="#consultationForm" class="btn-scroll-form">Đăng ký nhận tư vấn</a></div>
                        </div>
                    @endif
                </div>
            </div>

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
                            <a class="nav-link" data-toggle="tab" href="#tab-giaotrinh" role="tab" aria-controls="tab-giaotrinh" aria-selected="false">Giáo trình</a>
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
                            <div class="product-detail__team">
                                <div class="row">
                                    <div class="col-md-4 col-xl-4">
                                        <div class="f-avatar">
                                            <img src="assets/img/image/teambox-1.jpg">
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-xl-8">
                                        <div class="f-content">
                                            <div class="entry-detail">
                                                <h3>Cô Bùi Thu Hà</h3>
                                                <p>Giảng viên tại Tomato</p>
                                                <ul>
                                                    <li>. Giáo viên tiếng Trung đã có rất nhiều năm giảng dạy, kinh nghiệm cao, Cô sở hữu kênh YOUTUBE TOP 1 về " học tiếng trung"</li>
                                                    <li>- "Trung tâm ngoại ngữ TOMATO Hải Phòng" đã giúp hàng trăm ngàn học viên học ngoại ngữ tại Việt Nam</li>
                                                    <li>Trình độ HSK 6.</li>
                                                    <li>8 năm kinh nghiệm giảng dạy.</li>
                                                </ul>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                                    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                                    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                                                    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-giaotrinh" role="tabpanel">
                            <div id="lessonbox-listpost" class="accordionJs product-detail__listPost">
                                @foreach($lessons as $lesson)
                                    <div class="panel">
                                        <h3 class="panel__title" data-toggle="collapse" data-target="#lessonbox-listpost-id-{{ $loop->index }}" aria-expanded="true" aria-controls="lessonbox-listpost-id-{{ $loop->index }}">
                                            {{ $lesson->title }}
                                        </h3>
                                        <div id="lessonbox-listpost-id-{{ $loop->index }}" class="collapse show" data-parent="#lessonbox-listpost">
                                            <div class="panel__entry">
                                                <ul>
                                                    @foreach($lesson->parts as $part)
                                                        <li>{{ $part->title }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
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

            <div class="product-detail__relate">
                <div class="title">
                    <h2 class="title__title">Khoá học liên quan</h2>
                </div>

                <div class="owl-carousel lessonbox-wrap-min lessonbox-related-slide">
                    @foreach($related_courses as $item)
                        @include('frontend.category.course_item', [ 'course' => $item ])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section bg-gray" id="consultationForm">
    <div class="container">
        <div class="consultationForm">
            <div class="row no-gutters">
                <div class="col-md-6">
                    <div class="consultationForm__content">
                        <div class="consultationForm__fix">
                            <h2 class="consultationForm__title">Đăng ký nhận tin</h2>
                            <form class="consultationForm__form">
                                <div class="input-item">
                                    <div class="input-item__inner">
                                        <input type="text" name="name" placeholder="Họ và tên" class="form-control">
                                    </div>
                                </div>
                                <div class="input-item">
                                    <div class="input-item__inner">
                                        <input type="text" name="phone" placeholder="Số điện thoại" class="form-control">
                                    </div>
                                </div>
                                <div class="input-item">
                                    <div class="input-item__inner">
                                        <input type="text" name="email" placeholder="Email" class="form-control">
                                    </div>
                                </div>
                                <div class="input-item">
                                    <div class="input-item__inner">
                                        <select class="form-control" name="course">
                                            <option>Khoá học tiếng Hàn</option>
                                            <option>Khoá học tiếng Trung</option>
                                            <option>Khoá học tiếng Nhật</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="input-item">
                                    <div class="input-item__inner">
                                        <textarea type="text" name="name" placeholder="Nội dung" class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="button-item">
                                    <button type="submit" class="btn">Nhận tư vấn</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="consultationForm__bg" style="background-image: url(assets/img/image/consultationForm-bg.jpg);"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
