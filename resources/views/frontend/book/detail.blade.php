@extends('frontend.master')

@section('header')
<title>{{ $book->meta_title ?? $book->title }}</title>
<meta content="description" value="{{ $book->meta_description ?? $book->description }}">
<meta property="og:title" content="{{ $book->og_title ?? $book->meta_title ?? $book->title }}">
<meta property="og:description" content="{{ $book->og_description ?? $book->meta_description ?? $book->description }}">
<meta property="og:url" content="{{ $book->url }}">
<meta property="og:image" content="{{ $book->og_image ?? $book->cover }}">
<meta property="og:type" content="website">
<link rel="canonical" href="{{ $book->url }}">
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
        <h1 class="page-title__title">{{ $book->title }}</h1>
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
                    <div class="product-detail__img">
                        <div class="book-detail-img">
                            <div class="owl-carousel">
                                @foreach ($book->detail_images as $image)
                                    <div class="book-detail-img-block">
                                        <img src="{{ $image }}" alt="{{ $book->title }}">
                                    </div>
                                @endforeach
                            </div>
                            <ul class="owl-dot-custom owl-dots">
                                @foreach ($book->detail_images as $image)
                                    <li class="owl-dot">
                                        <img src="{{ $image }}" alt="{{ $book->title }}">
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-5">
                    <div class="product-detail__price">
                        <div>
                            <ins>{{ currency($book->price) }}</ins>
                            @if($book->original_price)
                                <del>{{ currency($book->original_price) }}</del>
                            @endif
                        </div>

                        @if($book->original_price)
                            <span class="sale">-{{ ceil(100 - $book->price / $book->original_price * 100) }}%</span>
                        @endif
                    </div>

                    <div class="product-detail__meta">
                        {!! $book->description !!}
                    </div>

                    @if(auth()->check())
                        <form action="{{ route('cart.add') }}" id="add-to-cart">
                            <div class="product-detail__quantity">
                                <label>Số lượng: </label>
                                <div class="input-quantity">
                                    <input type="number" name="amount" class="input-quantity-text form-control" value="1" data-min="1">
                                    <button type="button" class="input-quantity-number input-quantity-down">-</button>
                                    <button type="button" class="input-quantity-number input-quantity-up">+</button>
                                </div>
                            </div>

                            <div class="product-detal__btn">
                                <div class="btn-wrap">
                                    <button type="button" data-form="#add-to-cart" data-redirect="{{ route('cart') }}" class="btn btn-buy-now">Mua ngay</button>
                                    <button type="button" data-form="#add-to-cart" class="btn btn--secondary btn-add-to-cart">
                                        <span class="add-to-cart-text">Thêm vào giỏ</span>
                                        <span class="loading-text"><i class="fa fa-opencart"></i> Đang thêm...</span>
                                        <span class="complete-text"><i class="fa fa-check"></i> Đã thêm</span>
                                    </button>
                                </div>
                                <div class="btn-min">hoặc <a href="#consultationForm" class="btn-scroll-form">Đăng ký nhận tư vấn</a></div>
                            </div>
                            <input type="hidden" name="object_id" value="{{ $book->id }}">
                            <input type="hidden" name="type" value="{{ \App\Constants\ObjectType::BOOK }}">
                        </form>
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
                            <a class="nav-link" data-toggle="tab" href="#tab-khoahoc" role="tab" aria-controls="tab-khoahoc" aria-selected="false">Khoá học đi kèm</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-binhluan" role="tab" aria-controls="tab-giaotrinh" aria-selected="false">Bình luận</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="tabgioithieu" role="tabpanel">
                            <div class="entry-detail">
                                {!! $book->content !!}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-khoahoc" role="tabpanel">
                            <div class="owl-carousel lessonbox-wrap-min lessonbox-related-slide">
                                @foreach($related_courses as $item)
                                    @include('frontend.category.course_item', [ 'course' => $item ])
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-binhluan" role="tabpanel">
                            <div class="commentbox-wrap">
                                <div class="fb-comments" data-href="{{ $book->url }}" data-width="100%" data-numposts="10"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="product-detail__relate">
                <div class="title">
                    <h2 class="title__title">Sách liên quan</h2>
                </div>

                <div class="owl-carousel" data-slide-four-item>
                    <div class="bookBox">
                        <a href="#" class="bookBox__img">
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
                    </div>
                    <div class="bookBox">
                        <a href="#" class="bookBox__img">
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
                    </div>
                    <div class="bookBox">
                        <a href="#" class="bookBox__img">
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
                    </div>
                    <div class="bookBox">
                        <a href="#" class="bookBox__img">
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
                    </div>
                    <div class="bookBox">
                        <a href="#" class="bookBox__img">
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
                    </div>
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
                            <div class="consultationForm__title">Đăng ký nhận tin</div>
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
