@extends('frontend.master')

@section('header')
<title>{{ config('settings.homepage_title') }}</title>
<meta name="keywords" content="{{ config('settings.homepage_keywords') }}">
<meta name="description" content="{{ config('settings.homepage_description') }}">
<link rel="canonical" href="{{ route('home') }}">
<meta property="og:title" content="{{ config('settings.homepage_og_title') }}">
<meta property="og:description" content="{{ config('settings.homepage_og_description') }}">
<meta property="og:url" content="{{ route('home') }}">
<meta property="og:image" content="{{ config('settings.homepage_og_image') }}">
<meta property="og:type" content="website">
@endsection

@section('headings')
{!! config('settings.homepage_headings') !!}
@endsection

@section('body')
<section class="section sec-hero">
    <div class="sec-hero__slide owl-carousel wow fadeInRight" data-slide-one-item>
        @foreach(get_posts(null, 'slider') as $item)
            <div class="item">
                <a href="{{ $item->url }}">
                    <img src="{{ $item->cover }}" alt="{{ $item->title }}">
                </a>
            </div>
        @endforeach
    </div>
    <div class="sec-hero__sidebar wow fadeInLeft">
        <div class="f-title">Danh mục khoá học</div>
        <ul class="f-list">
            @foreach(get_categories(null, 'course-categories') as $c1)
                <li class="{{ $c1->__subcategory_count > 0 ? 'menu-has-children' : null }}">
                    <a href="{{ $c1->url }}">
                        <img src="{{ $c1->icon }}">
                        {{ $c1->title }}
                    </a>
                    @if($c1->__subcategory_count > 0)
                        <ul class="submenu">
                            @foreach(get_categories($c1->id, 'course-categories') as $c2)
                                <li class="{{ $c2->__subcategory_count > 0 ? 'menu-has-children' : null }}">
                                    <a href="{{ $c2->url }}">{{ $c2->title }}</a>
                                    @if($c2->__subcategory_count > 0)
                                        <ul class="submenu">
                                            @foreach(get_categories($c2->id, 'course-categories') as $c3)
                                                <li class="">
                                                    <a href="{{ $c3->url }}">{{ $c3->title }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                <div class="title  wow fadeInUp">
                    <div class="title__title">Giáo trình học online Tomato</div>
                </div>
            </div>
        </div>

        <div class="lessonbox-wrap">
            @foreach(get_categories(null, 'home-courses') as $category)
                <div class="lessonbox-wrap__item wow fadeInUp" data-wow-delay=".2s">
                    <div class="lessonbox-wrap__header">
                        <div class="lessonbox-wrap__title"><a href="{{ $category->url }}" class="font-weight-bold">{{ $category->title }}</a></div>
                        <a href="{{ $category->url }}" class="btn-link">Xem tất cả <i class="fa fa-angle-right"></i></a>
                    </div>

                    <div class="lessonbox-wrap__slide owl-carousel fixheight" data-slide-three-item>
                        @foreach(get_courses($category->id, 'home-courses') as $course)
                            @include('frontend.category.course_item', [ 'course' => $course ])
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                <div class="title wow fadeInUp">
                    <div class="title__title">Tin tức nổi bật</div>
                </div>
            </div>
        </div>

        <div class="post-slide owl-carousel fixheight wow fadeInUp" data-wow-delay=".2s" data-slide-three-item>
            @foreach(get_posts(null, 'hot-news') as $post)
                <div class="post">
                    <div class="post__inner">
                        <a href="{{ $post->url }}" class="post__img">
                            <img src="{{ $post->thumbnail }}" alt="{{ $post->title }}">
                        </a>
                        <div class="post__body">
                            <div class="post__meta">
                                @if($post->category)
                                    <span class="meta-cat"><a href="{{ $post->category->url }}">{{ $post->category->title }}</a></span>
                                @endif
                                <span class="meta-date">{{ $post->updated_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="post__title font-weight-bold">
                                <a href="{{ $post->url }}">{{ $post->title }}</a>
                            </div>
                            <p class="post__text">{{ $post->description }}</p>
                            <a href="{{ $post->url }}" class="btn btn--sm btn--outline">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                <div class="title wow fadeInUp">
                    <div class="title__title">Thư viện sách Tomato</div>
                </div>
            </div>
        </div>

        <div class="owl-carousel bookBox-slide wow fadeInUp" data-wow-delay=".2s" data-slide-four-item>
            @foreach (get_books(null, 'home-books') as $book)
            <div class="bookBox">
                <a href="{{ $book->url }}" class="bookBox__img">
                    <img src="{{ $book->thumbnail }}" alt="{{ $book->title }}">
                    @if($book->original_price)
                        <span class="sale">-{{ ceil(100 - $book->price / $book->original_price * 100) }}%</span>
                    @endif
                </a>
                <div class="bookBox__body">
                    <div class="bookBok__title"><a href="{{ $book->url }}">{{ $book->title }}</a></div>
                    <div class="bookBok__price">
                        <ins>{{ currency($book->price) }}</ins>
                        @if($book->original_price)
                            <del>{{ currency($book->original_price) }}</del>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section section-half section-testWhy">
    <div class="row">
        <div class="col-lg-6 order-lg-2">
            <div class="section-testWhy__inner last">
                <div class="wow fadeInRight" data-wow-delay=".2s">
                    <div class="title">
                        <div class="title__title">Tại sao chọn Tomato Online</div>
                    </div>
                    <div class="row">
                        <div class="col-4 col-xl-4">
                            <div class="featuredText">
                                <span class="featuredText__icon"><img src="{{ asset('tomato/assets/img/icon/icon-teacher.svg') }}"></span>
                                <div class="featuredText__title">Giảng viên</div>
                                <p class="featuredText__text">có nhiều năm kinh nghiệm</p>
                            </div>
                        </div>
                        <div class="col-4 col-xl-4">
                            <div class="featuredText">
                                <span class="featuredText__icon"><img src="{{ asset('tomato/assets/img/icon/icon-listF.svg') }}"></span>
                                <div class="featuredText__title">Bài giảng</div>
                                <p class="featuredText__text">dựa theo tài liệu mới nhất</p>
                            </div>
                        </div>
                        <div class="col-4 col-xl-4">
                            <div class="featuredText">
                                <span class="featuredText__icon"><img src="{{ asset('tomato/assets/img/icon/icon-platform.svg') }}"></span>
                                <div class="featuredText__title">Đa nền tảng</div>
                                <p class="featuredText__text">chỉ cần thiết bị có kết nối internet</p>
                            </div>
                        </div>
                        <div class="col-4 col-xl-4">
                            <div class="featuredText">
                                <span class="featuredText__icon"><img src="{{ asset('tomato/assets/img/icon/icon-24-house.svg') }}"></span>
                                <div class="featuredText__title">24/7</div>
                                <p class="featuredText__text">bất kể thời gian nào</p>
                            </div>
                        </div>
                        <div class="col-4 col-xl-4">
                            <div class="featuredText">
                                <span class="featuredText__icon"><img src="{{ asset('tomato/assets/img/icon/icon-payOne.svg') }}"></span>
                                <div class="featuredText__title">Thanh toán 1 lần</div>
                                <p class="featuredText__text">linh hoạt và nhanh gọn</p>
                            </div>
                        </div>
                        <div class="col-4 col-xl-4">
                            <div class="featuredText">
                                <span class="featuredText__icon"><img src="{{ asset('tomato/assets/img/icon/icon-saleF.svg') }}"></span>
                                <div class="featuredText__title">Ưu đãi</div>
                                <p class="featuredText__text">luôn luôn được cập nhật</p>
                            </div>
                        </div>
                        <div class="col-4 col-xl-4">
                            <div class="featuredText">
                                <span class="featuredText__icon"><img src="{{ asset('tomato/assets/img/icon/icon-faqF.svg') }}"></span>
                                <div class="featuredText__title">Giải đáp</div>
                                <p class="featuredText__text">mọi câu hỏi của học viên</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center pt-20">
                        <a href="#consultationForm" class="btn btn-scroll-form">Đăng ký khoá học</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 order-lg-1">
            <div class="section-testWhy__inner first" style="background-image: url({{ asset('tomato/assets/img/image/bg-testimonial.jpg') }})">
                <div class="bg-overlay"></div>

                <div class="f-fix wow fadeInLeft">
                    <div class="title">
                        <div class="title__title">Cảm nhận của học viên</div>
                    </div>

                    <div class="testimonial-slide">
                        <div class="owl-carousel">
                            {{-- <img src="{{ asset('images/testimonial/testimonial-hoang-cam-tu.png') }}" alt="">
                            <img src="{{ asset('images/testimonial/testimonial-khanh.png') }}" alt="">
                            <img src="{{ asset('images/testimonial/testimonial-nguyen-thanh-mai.png') }}" alt="">
                            <img src="{{ asset('images/testimonial/testimonial-nguyen-thi-ngoc-yen.png') }}" alt="">
                            <img src="{{ asset('images/testimonial/testimonial-tuan-dinh.png') }}" alt=""> --}}
                            <div class="testimonial">
                                <span class="testimonial__icon"><img src="{{ asset('tomato/assets/img/icon/icon-quote.svg') }}" alt=""></span>
                                <p class="testimonial__quote">Chị dạy dễ hiểu quá, cố gắng thực hiện giấc mơ đi du học của mình.</p>

                                <div class="testimonial__info">
                                    <div class="testimonial__name">Hoàng Cẩm Tú</div>
                                    <p class="testimonial__position">Học viên Youtube</p>
                                </div>
                            </div>
                            <div class="testimonial">
                                <span class="testimonial__icon"><img src="{{ asset('tomato/assets/img/icon/icon-quote.svg') }}" alt=""></span>
                                <p class="testimonial__quote">Em rất yêu thích bộ môn tiếng Trung và đã tìm được khoá học tại Tomato, rất cảm ơn trung tâm và cô Bùi Thu Hà đã cho em khoá học bổ ích.</p>
                                <div class="testimonial__info">
                                    <div class="testimonial__name">Khanh Mmui</div>
                                    <p class="testimonial__position">Học viên đang theo học</p>
                                </div>
                            </div>
                            <div class="testimonial">
                                <span class="testimonial__icon"><img src="{{ asset('tomato/assets/img/icon/icon-quote.svg') }}" alt=""></span>
                                <p class="testimonial__quote">Tomato, trung tâm uy tín, giáo viên thân thiện, nhiệt tình rất đáng đặt niềm tin.</p>
                                <div class="testimonial__info">
                                    <div class="testimonial__name">Nguyễn Thanh Mai</div>
                                    <p class="testimonial__position">Học viên đang theo học</p>
                                </div>
                            </div>
                            <div class="testimonial">
                                <span class="testimonial__icon"><img src="{{ asset('tomato/assets/img/icon/icon-quote.svg') }}" alt=""></span>
                                <p class="testimonial__quote">Chỉ nói 1 câu: Mua khoá học ở Tomato không phí tiền. Đặt niềm tin đúng chỗ.</p>
                                <div class="testimonial__info">
                                    <div class="testimonial__name">Nguyễn Thị Ngọc Yến</div>
                                    <p class="testimonial__position">Học viên đang theo học</p>
                                </div>
                            </div>
                            <div class="testimonial">
                                <span class="testimonial__icon"><img src="{{ asset('tomato/assets/img/icon/icon-quote.svg') }}" alt=""></span>
                                <p class="testimonial__quote">Mình đã học nhiều nơi, nhưng đây là môi trường từ nhân viên đến giáo viên đều rất nhiệt tình và tận tâm.</p>
                                <div class="testimonial__info">
                                    <div class="testimonial__name">Tuấn Đinh</div>
                                    <p class="testimonial__position">Học viên đang theo học</p>
                                </div>
                            </div>
                        </div>
                        <ul class="owl-dot-custom">
                            <li class="owl-dot"><span style="background-image: url({{ asset('images/testimonial/avatar-hoang-cam-tu.jpg') }})"></span></li>
                            <li class="owl-dot"><span style="background-image: url({{ asset('images/testimonial/avatar-khanh.jpg') }})"></span></li>
                            <li class="owl-dot"><span style="background-image: url({{ asset('images/testimonial/avatar-nguyen-thanh-mai.jpg') }})"></span></li>
                            <li class="owl-dot"><span style="background-image: url({{ asset('images/testimonial/avatar-nguyen-thi-ngoc-yen.jpg') }})"></span></li>
                            <li class="owl-dot"><span style="background-image: url({{ asset('images/testimonial/avatar-tuan-dinh.jpg') }})"></span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section section-faq-video">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                <div class="title wow fadeInUp">
                    <div class="title__title">Câu hỏi thường gặp</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div id="faq" class="accordionJs wow fadeInLeft" data-wow-delay=".2s">
                    <div class="panel">
                        <div class="panel__title" data-toggle="collapse" data-target="#faq1" aria-expanded="true" aria-controls="faq1">
                            Tôi không rành về vi tính, vậy tôi có học được không?
                        </div>
                        <div id="faq1" class="collapse show" data-parent="#faq">
                            <div class="panel__entry">
                                <p>Lớp học Online có thao tác cực kỳ đơn giản bất kỳ ai cũng có thể thực
                                    hiện được.</p>
                                <p>Trước khi lớp học khai giảng, từng học viên sẽ được kỹ thuật viên cài đặt
                                    máy, phần mềm đồng thời hướng dẫn sử dụng.</p>
                                <p>Trong quá trình học, các học viên được hỗ trợ khi lớp đang học và khi kết
                                    thúc giờ học. Hỗ trợ từ xa thông qua teamviewer hoặc hỗ trợ trực tiếp
                                    tại trường Việt Nhật.</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel__title" data-toggle="collapse" data-target="#faq2" aria-expanded="false" aria-controls="faq2">
                            Tôi cần chuẩn bị gì cho lớp học Online này?
                        </div>
                        <div id="faq2" class="panel__content collapse" data-parent="#faq">
                            <div class="panel__entry">
                                <p>Để học được lớp bạn tất yếu phải có 1 máy tính xách tay hoặc máy bàn, có
                                    headphone và microphone. Máy có kết nối Internet.</p>
                                <p>Ngoài ra bạn cần phải chuẩn bị bút chì, tập viết ghi chú lại trong khi
                                    học. Nếu có máy in thì càng tốt, các bạn có thể in bài ra học hoặc cũng
                                    có thể học trực tiếp trên máy tính.</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel__title" data-toggle="collapse" data-target="#faq3" aria-expanded="false" aria-controls="faq3">
                            Những nội dung học online tôi có thể nhận được tài liệu bằng file word hay
                            không?
                        </div>
                        <div id="faq3" class="panel__content collapse" data-parent="#faq">
                            <div class="panel__entry">
                                <p>Không, chúng tôi soạn bài trên nền Web do đó không có file Word.</p>
                                <p>Nội dung các bài học, các bạn có thể tự in ra hoặc tự copy qua word để in
                                    ra học.</p>
                                <p>Trong quá trình học, có thể giáo viên sẽ gởi 1 số tài liệu tham khảo khác
                                    dạng file âm thanh hoặc video hoặc word,... tuỳ theo yêu cầu học của
                                    giáo viên.</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel__title" data-toggle="collapse" data-target="#faq4" aria-expanded="false" aria-controls="faq4">
                            Sau khi học xong có được làm bài thi thử không?
                        </div>
                        <div id="faq4" class="panel__content collapse" data-parent="#faq">
                            <div class="panel__entry">
                                <p>Chương trình học có kỳ thi thử.</p>
                                <p>Trong quá trình học, có giải đề ứng với mẫu ngữ pháp và giải đề để tăng
                                    cường dokkai...</p>
                                <p>1 khoá học có 4-5 bài test sau mỗi 1 đến 2 tháng. Các bài test này cũng
                                    dựa trên các đề thực tế của kỳ thi năng lực Nhật ngữ.</p>
                                <p>Trước mỗi kỳ thi JLPT vào tháng 7 và tháng 12, sẽ có làm đề thi thử và
                                    giải đề thi. Trung bình 4-5 đề thi hoàn chỉnh cho 1 đợt thi. Khi làm bài
                                    thi này, học viên sẽ làm đúng thời gian và làm đầy đủ các phần như 1 kỳ
                                    thi JLPT thực tế. Sau khi thi giáo viên sẽ hướng dẫn cách làm bài và sửa
                                    bài thi.</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel__title" data-toggle="collapse" data-target="#faq5" aria-expanded="false" aria-controls="faq5">
                            Có thể học online qua Ipad hay điện thoại di động không?
                        </div>
                        <div id="faq5" class="panel__content collapse" data-parent="#faq">
                            <div class="panel__entry">
                                <p>Chương trình học chỉ chạy ổn định trên máy tính bàn hoặc laptop. Trường
                                    hợp mất kết nối do hư mạng Inter.net bạn có thể dùng tạm nhưng có 1 số
                                    chức năng sẽ bị hạn chế.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="video-demo wow fadeInRight" data-wow-delay=".2s" style="background-image: url({{ asset('tomato/assets/img/image/huong-dan-cach-nap-tien-online.jpeg') }});">
                    <div class="bg-overlay"></div>
                    <div class="video-demo__btn">
                        <a href="https://www.youtube.com/watch?v=r1r7O5HsBMY" data-init="magnificPopupVideo"><i class="pe-icon-play"></i> Hướng dẫn đăng ký</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="ctabox wow fadeInUp">
            <span class="ctabox__icon"><img src="{{ asset('tomato/assets/img/icon/icon-ctabox.svg') }}"></span>
            <div class="ctabox__wrap">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="ctabox__title">Đăng ký nhận tư vấn để được tham gia vào môi trường dạy và
                            học ngoại ngữ Online tốt nhất Hải Phòng</div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ctabox__btn">
                            <a href="#consultationForm" class="btn btn-scroll-form">Đăng ký tư vấn</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                <div class="title wow fadeInUp">
                    <div class="title__title">Đội ngũ giáo viên</div>
                </div>
            </div>
        </div>

        <div class="team-slide-mobile wow fadeInUp" data-wow-delay=".2s">
            <div class="owl-carousel">
                @foreach(get_teachers() as $item)
                    <div class="item">
                        <div class="teambox">
                            <div class="teambox__img">
                                <img src="{{ $item->avatar }}">
                            </div>

                            <div class="teambox__body">
                                <div class="teambox__name">{{ $item->name }}</div>
                                <p class="teambox__position">Giảng viên</p>
                            </div>

                            <div class="teambox__hover">
                                <div class="h3">{{ $item->name }}</div>
                                <div class="desc">{!! $item->description !!}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="section" id="consultationForm">
    <div class="container">
        <div class="consultationForm">
            <div class="row no-gutters">
                <div class="col-md-6">
                    <div class="consultationForm__content">
                        <div class="consultationForm__fix wow fadeInUp">
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
                                            @foreach (get_categories(null, 'course-categories') as $item)
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
                    <div class="consultationForm__bg wow fadeInUp" data-wow-delay=".2s" style="background-image: url({{ asset('tomato/assets/img/image/dang_ky_nhan_tin.jpg') }});"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
