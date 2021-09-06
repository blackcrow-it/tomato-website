@extends('frontend.master')

@section('header')
<title>{{ $combo_course->meta_title ?? $combo_course->title }}</title>
<meta content="description" value="{{ $combo_course->meta_description ?? $combo_course->description }}">
<meta property="og:title" content="{{ $combo_course->og_title ?? $combo_course->meta_title ?? $combo_course->title }}">
<meta property="og:description" content="{{ $combo_course->og_description ?? $combo_course->meta_description ?? $combo_course->description }}">
<meta property="og:url" content="{{ $combo_course->url }}">
<meta property="og:image" content="{{ $combo_course->og_image ?? $combo_course->cover }}">
<meta property="og:type" content="website">
<link rel="canonical" href="{{ $combo_course->url }}">
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
        <h1 class="page-title__title">{{ $combo_course->title }}</h1>
    </div>
</section>

<section class="section wow">
    <div class="container">
        <div class="layout layout--left">
            <div class="row">
                <div class="col-xl-3">
                    <div class="layout-sidebar">
                        <div class="widget widget--lessonCat">
                            <h2 class="widget__title">Combo Khoá học</h2>
                            <div class="f-scroll">
                                <ul>
                                    <li class="current"><a href="#">Tiếng trung</a></li>
                                    <li><a href="#">Tiếng Hàn</a></li>
                                    <li><a href="#">Nhật</a></li>
                                </ul>
                            </div>
                        </div>
                        {{-- <div class="widget widget--mockTest d-none d-xl-block">
                            <h2 class="widget__title">Kiểm tra kiến thức</h2>
                            <div class="f-content">
                                <a href="thithudauvao.html" class="btn btn--secondary">Vào thi</a>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="layout-content">
                        <div class="row spacing-custom lessonbox-wrap-style">
                            {{-- {{dd($combo_course->items()->first()->course)}} --}}
                            @foreach ($combo_course->items as $item)
                            <div class="col-6 col-lg-4">
                                <div class="lessonbox">
                                    <div class="lessonbox__inner">
                                        <a href="{{ $item->course->url }}" class="lessonbox__img">
                                            <img src="{{ $item->course->thumbnail }}">
                                        </a>
                                        <div class="lessonbox__body">
                                            @if($item->course->category)
                                                <div class="lessonbox__cat">
                                                    <a href="{{ $item->course->category->url }}">{{ $item->course->category->title }}</a>
                                                </div>
                                            @endif
                                            <h3 class="lessonbox__title"><a href="{{ $item->course->url }}">{{ $item->course->title }}</a></h3>
                                            <ul class="lessonbox__info">
                                                <li>Bài học: {{ $item->course->lessons()->count() }} bài</li>
                                                <li>Giảng viên: <a href="#">{{$item->course->teacher->name}}</a></li>
                                                @switch($item->course->level)
                                                    @case(\App\Constants\CourseLevel::ELEMENTARY)
                                                        <li>Trình độ: Sơ cấp</li>
                                                        @break
                                                    @case(\App\Constants\CourseLevel::INTERMEDIATE)
                                                        <li>Trình độ: Trung cấp</li>
                                                        @break
                                                    @case(\App\Constants\CourseLevel::ADVANCED)
                                                        <li>Trình độ: Cao cấp</li>
                                                        @break
                                                    @default
                                                        <li>Trình độ: Không phân loại</li>
                                                        @break
                                                @endswitch
                                                {{-- <li>Đánh giá:
                                                    <span class="lessonbox__rating">
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star-o"></i>
                                                        <i class="fa fa-star-o"></i>
                                                    </span>
                                                    (4.5)
                                                </li> --}}
                                            </ul>

                                            <div class="lessonbox__footer">
                                                <div class="lessonbox__price">
                                                    <ins>{{ currency($item->course->price) }}</ins>
                                                    @if($item->course->original_price)
                                                        <del>{{ currency($item->course->original_price) }}</del>
                                                    @endif
                                                </div>
                                                <a href="{{ $item->course->url }}" class="btn btn--sm btn--outline">Chi tiết</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="product-detail__detail">
                            <div class="tabJs">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#tabgioithieu" role="tab" aria-controls="tabgioithieu" aria-selected="true">Mua combo</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tab-tailieu" role="tab" aria-controls="tab-tailieu" aria-selected="false">Gói combo liên quan</a>
                                    </li>
                                    {{-- <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tab-danhgia" role="tab" aria-controls="tab-giaotrinh" aria-selected="false">Đánh giá</a>
                                    </li> --}}
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="tabgioithieu" role="tabpanel">
                                        <div class="giacombo">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Tên khoá học</th>
                                                        <th>Giá</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($combo_course->items as $item)
                                                    <tr>
                                                        <td><a href="{{$item->course->url}}">{{ $item->course->title }}</a></td>
                                                        <td>{{currency($item->course->price)}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td>Giá</td>
                                                        <?php
                                                            $price_origin = 0;
                                                            foreach ($combo_course->items as $item) {
                                                                $price_origin += $item->course->price;
                                                            }
                                                        ?>
                                                        <td><b>{{ currency($price_origin) }}</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Khuyến mãi</td>
                                                        <td class="sale">{{ currency($combo_course->price - $price_origin) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tổng tiền</td>
                                                        <td class="tongtien"><b>{{ currency($combo_course->price) }}</b><br><br><a href="#" class="btn">Mua combo</a></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="tab-tailieu" role="tabpanel">
                                        <div class="owl-carousel lessonbox-wrap-min combo-ralete-slide">
                                            <div class="lessonbox">
                                                <div class="lessonbox__inner">
                                                    <div class="lessonbox__body">
                                                        <h3 class="lessonbox__title"><a href="combo-khoahoc.html">Combo khoá học tiếng Trung GT1 + GT2</a></h3>
                                                        <div class="lessonbox__footer">
                                                            <div class="lessonbox__price">
                                                                <ins>499.000đ</ins>
                                                                <del>1.000.000đ</del>
                                                            </div>
                                                            <a href="combo-khoahoc.html" class="btn btn--sm btn--outline">Chi tiết</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="lessonbox">
                                                <div class="lessonbox__inner">
                                                    <div class="lessonbox__body">
                                                        <h3 class="lessonbox__title"><a href="combo-khoahoc.html">Combo khoá học tiếng Hàn GT1 + GT2</a></h3>
                                                        <div class="lessonbox__footer">
                                                            <div class="lessonbox__price">
                                                                <ins>499.000đ</ins>
                                                                <del>1.000.000đ</del>
                                                            </div>
                                                            <a href="combo-khoahoc.html" class="btn btn--sm btn--outline">Chi tiết</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="lessonbox">
                                                <div class="lessonbox__inner">
                                                    <div class="lessonbox__body">
                                                        <h3 class="lessonbox__title"><a href="combo-khoahoc.html">Combo khoá học tiếng Nhật GT1 + GT2</a></h3>
                                                        <div class="lessonbox__footer">
                                                            <div class="lessonbox__price">
                                                                <ins>499.000đ</ins>
                                                                <del>1.000.000đ</del>
                                                            </div>
                                                            <a href="combo-khoahoc.html" class="btn btn--sm btn--outline">Chi tiết</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="lessonbox">
                                                <div class="lessonbox__inner">
                                                    <div class="lessonbox__body">
                                                        <h3 class="lessonbox__title"><a href="combo-khoahoc.html">Combo khoá học tiếng Nhật GT1 + GT2</a></h3>
                                                        <div class="lessonbox__footer">
                                                            <div class="lessonbox__price">
                                                                <ins>499.000đ</ins>
                                                                <del>1.000.000đ</del>
                                                            </div>
                                                            <a href="combo-khoahoc.html" class="btn btn--sm btn--outline">Chi tiết</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="tab-danhgia" role="tabpanel">
                                        <div class="courses-review">
                                            <h3 class="title-fz-22">Điểm đánh giá</h3>
                                            <div class="r-header">
                                                <div class="r-point">
                                                    <div class="r-point__inner">
                                                        <h3 class="r-1">4.93</h3>
                                                        <h4 class="r-2">Course rating</h4>
                                                        <p class="r-3"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></p>
                                                    </div>
                                                </div>
                                                <div class="r-progress">
                                                    <ul class="r-progress__ul">
                                                        <li class="r-progress__li">
                                                            <div class="r-progress__step">
                                                                <span style="width: 90%"></span>
                                                            </div>
                                                            <p class="r-progress__star">
                                                                <span>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star-o"></i>
                                                                </span>
                                                                4132
                                                            </p>
                                                        </li>
                                                        <li class="r-progress__li">
                                                            <div class="r-progress__step">
                                                                <span style="width: 50%"></span>
                                                            </div>
                                                            <p class="r-progress__star">
                                                                <span>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star-o"></i>
                                                                    <i class="fa fa-star-o"></i>
                                                                </span>
                                                                12
                                                            </p>
                                                        </li>
                                                        <li class="r-progress__li">
                                                            <div class="r-progress__step">
                                                                <span style="width: 30%"></span>
                                                            </div>
                                                            <p class="r-progress__star">
                                                                <span>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star-o"></i>
                                                                    <i class="fa fa-star-o"></i>
                                                                    <i class="fa fa-star-o"></i>
                                                                </span>
                                                                5
                                                            </p>
                                                        </li>
                                                        <li class="r-progress__li">
                                                            <div class="r-progress__step">
                                                                <span style="width: 20%"></span>
                                                            </div>
                                                            <p class="r-progress__star">
                                                                <span>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star-o"></i>
                                                                    <i class="fa fa-star-o"></i>
                                                                    <i class="fa fa-star-o"></i>
                                                                    <i class="fa fa-star-o"></i>
                                                                </span>
                                                                2
                                                            </p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="commentbox">
                                                <div class="commentList">
                                                    <ul class="commentList__item">
                                                        <li>
                                                            <div class="commentList__inner">
                                                                <div class="commentList__avatar">
                                                                    <img src="assets/img/icon/icon-avatar-comment.jpg" alt="">
                                                                </div>
                                                                <div class="commentList__body">
                                                                    <h3 class="commentList__name">Nguyễn Quốc Khánh</h3>
                                                                    <p class="commentList__text">Thầy ơi!! Tư vấn khóa combo N4-3, và bộ giáo trình kèm theo với ạ! cảm ơn ạ!!</p>
                                                                    <div class="commentList__meta">
                                                                        <span class="meta-date"><i class="fa fa-clock-o"></i>14-05-2020 11:34</span>
                                                                    </div>
                                                                    <div class="commentList__star">
                                                                        <i class="fa fa-star"></i>
                                                                        <i class="fa fa-star"></i>
                                                                        <i class="fa fa-star"></i>
                                                                        <i class="fa fa-star-o"></i>
                                                                        <i class="fa fa-star-o"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="commentList__inner">
                                                                <div class="commentList__avatar">
                                                                    <img src="assets/img/icon/icon-avatar-comment.jpg" alt="">
                                                                </div>
                                                                <div class="commentList__body">
                                                                    <h3 class="commentList__name">Nguyễn Quốc Khánh</h3>
                                                                    <p class="commentList__text">Thầy ơi!! Tư vấn khóa combo N4-3, và bộ giáo trình kèm theo với ạ! cảm ơn ạ!!</p>
                                                                    <div class="commentList__meta">
                                                                        <span class="meta-date"><i class="fa fa-clock-o"></i>14-05-2020 11:34</span>
                                                                    </div>
                                                                    <div class="commentList__star">
                                                                        <i class="fa fa-star"></i>
                                                                        <i class="fa fa-star"></i>
                                                                        <i class="fa fa-star"></i>
                                                                        <i class="fa fa-star-o"></i>
                                                                        <i class="fa fa-star-o"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="commentList__inner">
                                                                <div class="commentList__avatar">
                                                                    <img src="assets/img/icon/icon-avatar-comment.jpg" alt="">
                                                                </div>
                                                                <div class="commentList__body">
                                                                    <h3 class="commentList__name">Nguyễn Quốc Khánh</h3>
                                                                    <p class="commentList__text">Thầy ơi!! Tư vấn khóa combo N4-3, và bộ giáo trình kèm theo với ạ! cảm ơn ạ!!</p>
                                                                    <div class="commentList__meta">
                                                                        <span class="meta-date"><i class="fa fa-clock-o"></i>14-05-2020 11:34</span>
                                                                    </div>
                                                                    <div class="commentList__star">
                                                                        <i class="fa fa-star"></i>
                                                                        <i class="fa fa-star"></i>
                                                                        <i class="fa fa-star"></i>
                                                                        <i class="fa fa-star-o"></i>
                                                                        <i class="fa fa-star-o"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="addReviewBox">
                                                <div class="f-header">
                                                    <h3 class="title-fz-22">Thêm đánh giá</h3>
                                                    <p class="f-header__text">What is it like to Course?</p>
                                                    <div class="f-header__star">
                                                        <input id="radio1" type="radio" name="estrellas" value="5">
                                                        <label for="radio1"></label>
                                                        <input id="radio2" type="radio" name="estrellas" value="4">
                                                        <label for="radio2"></label>
                                                        <input id="radio3" type="radio" name="estrellas" value="3">
                                                        <label for="radio3"></label>
                                                        <input id="radio4" type="radio" name="estrellas" value="2">
                                                        <label for="radio4"></label>
                                                        <input id="radio5" type="radio" name="estrellas" value="1">
                                                        <label for="radio5"></label>
                                                    </div>
                                                </div>
                                                <form class="form-wrap">
                                                    <div class="input-item">
                                                        <label>Tên bạn</label>
                                                        <div class="input-item__inner">
                                                            <input type="text" name="phone" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="input-item">
                                                        <label>Nội dung</label>
                                                        <div class="input-item__inner">
                                                            <textarea type="text" name="name" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-actions">
                                                        <button type="submit" class="btn">Gửi đánh giá</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
            message: 'Bạn chắc chắn muốn mua khóa học <b>{{ $combo_course->title }}</b>?',
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
