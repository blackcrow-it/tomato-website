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
<style>
    .page-combo-course a {
        color: #FFFFFF;
        font-size: 12px;
        text-transform: capitalize;
        background: #e71d36;
        padding: 7px 11px 6px;
        margin: 0 2px 2px 0;
    }
    .page-combo-course a:hover {
        background: #77af41;
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
            </ol>
        </nav>
        <h1 class="page-title__title">{{ $course->title }}</h1><br/>
        @if (count($related_combos_course) > 0)
        <div class="page-combo-course">
            <span>Combo khoá học: </span>
            @foreach($related_combos_course as $item)
            <a type="button" href="{{$item->url}}" title="{{count($item->items)}} khoá ({{currency($item->price)}})">{{$item->title}}</a>
            @endforeach
        </div>
        @endif
    </div>
</section>

<section class="section">
    <div class="container">
        @include('frontend.session_alert')
        @if($status && $is_owned)
        <div class="alert alert-success">
            Bạn đã mua khóa học thành công.
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

                    @if(auth()->check())
                        @if(!$is_owned && $is_trial)
                        <div class="btn-wrap">
                            <a href="{{ route('course.start', [ 'id' => $course->id ]) }}" class="btn btn--sm"><i class="fa fa-book" aria-hidden="true"></i> Học thử</a>
                        </div><br/>
                        @endif
                    @endif

                    <div class="mb-3">
                        {!! $course->description !!}
                    </div>

                    @if(auth()->check())
                        @if($is_owned)
                            <a href="{{ route('course.start', [ 'id' => $course->id ]) }}" class="btn">Xem bài giảng</a>
                        @else
                            <div class="product-detal__btn">
                                <div class="btn-wrap">
                                    <form action="{{ route('cart.instant_buy') }}" method="POST" id="instant_buy_form">
                                        @csrf
                                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                                        <button type="submit" class="btn btn-buy-now">Mua ngay</button>
                                    </form>
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
                            <div class="mb-3">
                                <div class="sharethis-inline-share-buttons"></div>
                            </div>
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
                                                    <div class="h3">{{ $course->teacher->name }}</div>
                                                    {!! $course->teacher->description !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="tab-giaotrinh" role="tabpanel">
                            <div id="lessonbox-listpost" class="accordionJs product-detail__listPost">
                                @foreach($lessons as $lesson)
                                    <div class="panel">
                                        <div class="panel__title" data-toggle="collapse" data-target="#lessonbox-listpost-id-{{ $loop->index }}" aria-expanded="true" aria-controls="lessonbox-listpost-id-{{ $loop->index }}">
                                            {{ $lesson->title }}
                                        </div>
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
                                    @foreach($related_books as $item)
                                        @include('frontend.category.book_item', [ 'book' => $item ])
                                    @endforeach
                                </div>
                            </div>
                            <div style="margin-top: 50px;">
                                @if (count($related_combos_course) > 0)
                                <div class="owl-carousel lessonbox-wrap-min combo-ralete-slide">
                                    @foreach($related_combos_course as $item)
                                    <div class="lessonbox">
                                        <div class="lessonbox__inner">
                                            <div class="lessonbox__body">
                                                <h3 class="lessonbox__title"><a href="{{ $item->url }}">{{ $item->title }}</a></h3>
                                                <div class="lessonbox__footer">
                                                    <div class="lessonbox__price">
                                                        <?php
                                                            $price_origin = 0;
                                                            foreach ($item->items as $c_course) {
                                                                $price_origin += $c_course->course->price;
                                                            }
                                                        ?>
                                                        <ins>{{ currency($item->price) }}</ins>
                                                        @if ($item->price < $price_origin)
                                                            <del>{{ currency($price_origin) }}</del>
                                                        @endif
                                                    </div>
                                                    <a href="{{ $item->url }}" class="btn btn--sm btn--outline">Chi tiết</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div>Đang cập nhật ...</div>
                                @endif
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

            <div class="product-detail__relate">
                <div class="title">
                    <div class="title__title">Khoá học liên quan</div>
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
                                            @foreach(get_categories(null, 'course-categories') as $item)
                                                <option>{{ $item->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="input-item">
                                    <div class="input-item__inner">
                                        <textarea type="text" name="content" placeholder="Nội dung" class="form-control"></textarea>
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
                    @if ($consultation_background)
                        <div class="consultationForm__bg wow fadeInUp" data-wow-delay=".2s" style="background-image: url({{ $consultation_background }});"></div>
                    @else
                        <div class="consultationForm__bg wow fadeInUp" data-wow-delay=".2s" style="background-image: url({{ asset('tomato/assets/img/image/dang_ky_nhan_tin.jpg') }});"></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('footer')
<script>
    $('#instant_buy_form button[type="submit"]').click(function (e) {
        e.preventDefault();
        bootbox.confirm({
            message: 'Bạn chắc chắn muốn mua khóa học <b>{{ $course->title }}</b>?',
            buttons: {
                confirm: {
                    label: 'Xác nhận',
                    className: 'btn--sm btn--success'
                },
                cancel: {
                    label: 'Hủy bỏ',
                    className: 'btn--sm bg-dark'
                }
            },
            callback: r => {
                if (!r) return;
                $('#instant_buy_form').submit();
            }
        });
    });

</script>
<script>
    $('.entry-detail img').css('height', 'auto');
</script>
@endsection
