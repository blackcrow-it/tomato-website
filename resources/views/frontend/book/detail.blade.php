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
                    <img src="{{ $book->cover ?? $book->thumbnail }}" alt="{{ $book->title }}">
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

                    <div>
                        {!! $book->description !!}
                    </div>

                    @if(auth()->check())
                        <div class="product-detal__btn">
                            <div class="btn-wrap">
                                <button type="button" data-form="#add-to-cart" data-redirect="{{ route('cart.confirm') }}" class="btn btn-buy-now">Mua ngay</button>
                                <button type="button" data-form="#add-to-cart" class="btn btn--secondary btn-add-to-cart">
                                    <span class="add-to-cart-text">Thêm vào giỏ</span>
                                    <span class="loading-text"><i class="fa fa-opencart"></i> Đang thêm...</span>
                                    <span class="complete-text"><i class="fa fa-check"></i> Đã thêm</span>
                                </button>
                            </div>
                            <div class="btn-min">hoặc <a href="#consultationForm" class="btn-scroll-form">Đăng ký nhận tư vấn</a></div>
                        </div>
                        <form action="{{ route('cart.add') }}" id="add-to-cart" class="invisible">
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
                            <a class="nav-link active" data-toggle="tab" href="#tabgioithieu" role="tab" aria-controls="tabgioithieu" aria-selected="true">Nội dung</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-binhluan" role="tab" aria-controls="tab-giaotrinh" aria-selected="false">Bình luận</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="tabgioithieu" role="tabpanel">
                            <div class="entry-detail">{!! $book->content !!}</div>
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