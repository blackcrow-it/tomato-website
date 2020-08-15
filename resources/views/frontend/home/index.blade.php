@extends('frontend.master')

@section('header')
<title>Homepage</title>
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
        <h2 class="f-title">Danh mục khoá học</h2>
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
                    <h2 class="title__title">Giáo trình học online Tomato</h2>
                </div>
            </div>
        </div>

        <div class="lessonbox-wrap">
            @foreach(get_categories(null, 'home-courses') as $category)
                <div class="lessonbox-wrap__item wow fadeInUp" data-wow-delay=".2s">
                    <div class="lessonbox-wrap__header">
                        <h3 class="lessonbox-wrap__title"><a href="{{ $category->url }}">{{ $category->title }}</a></h3>
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

        <div class="text-center pt-40">
            <a href="thithudauvao.html" target="_blank" class="btn">Kiểm tra kiến thức</a>
        </div>
    </div>
</section>

<section class="section bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                <div class="title wow fadeInUp">
                    <h2 class="title__title">Tin tức nổi bật</h2>
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
                                @if ($post->category)
                                    <span class="meta-cat"><a href="{{ $post->category->url }}">{{ $post->category->title }}</a></span>
                                @endif
                                <span class="meta-date">{{ $post->updated_at->format('d/m/Y') }}</span>
                            </div>
                            <h3 class="post__title">
                                <a href="{{ $post->url }}">{{ $post->title }}</a>
                                </h3>
                            <p class="post__text">{{ $post->description }}</p>
                            <a href="{{ $post->url }}" class="btn btn--sm btn--outline">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center pt-30 wow fadeInUp" data-wow-delay=".2s">
            <a href="tintuc.html" class="btn">Xem tất cả</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                <div class="title wow fadeInUp">
                    <h2 class="title__title">Thư viện sách Tomato</h2>
                </div>
            </div>
        </div>

        <div class="owl-carousel bookBox-slide wow fadeInUp" data-wow-delay=".2s" data-slide-four-item>
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
            </div>
            <div class="bookBox">
                <a href="chitietsach.html" class="bookBox__img">
                    <img src="assets/img/image/bookBox-1.jpg" alt="">
                </a>
                <div class="bookBox__body">
                    <h3 class="bookBok__title"><a href="chitietsach.html">Giáo trình hán ngữ</a></h3>
                    <div class="bookBok__price">
                        <ins>499.000đ</ins>
                    </div>
                </div>
            </div>
            <div class="bookBox">
                <a href="chitietsach.html" class="bookBox__img">
                    <img src="assets/img/image/bookBox-1.jpg" alt="">
                </a>
                <div class="bookBox__body">
                    <h3 class="bookBok__title"><a href="chitietsach.html">Giáo trình hán ngữ</a></h3>
                    <div class="bookBok__price">
                        <ins>499.000đ</ins>
                    </div>
                </div>
            </div>
            <div class="bookBox">
                <a href="chitietsach.html" class="bookBox__img">
                    <img src="assets/img/image/bookBox-1.jpg" alt="">
                </a>
                <div lass="bookBox__body">
                    <h3 class="bookBok__title"><a href="chitietsach.html">Giáo trình hán ngữ</a></h3>
                    <div class="bookBok__price">
                        <ins>499.000đ</ins>
                    </div>
                </div>
            </div>
            <div class="bookBox">
                <a href="chitietsach.html" class="bookBox__img">
                    <img src="assets/img/image/bookBox-1.jpg" alt="">
                </a>
                <div class="bookBox__body">
                    <h3 class="bookBok__title"><a href="chitietsach.html">Giáo trình hán ngữ</a></h3>
                    <div class="bookBok__price">
                        <ins>499.000đ</ins>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center btn-padding wow fadeInUp">
            <a href="sach.html" class="btn">Xem tất cả</a>
        </div>
    </div>
</section>

<section class="section section-half section-testWhy">
    <div class="row">
        <div class="col-lg-6 order-lg-2">
            <div class="section-testWhy__inner last">
                <div class="wow fadeInRight" data-wow-delay=".2s">
                    <div class="title">
                        <h2 class="title__title">Tại sao chọn Tomato Online</h2>
                    </div>
                    <div class="row">
                        <div class="col-4 col-xl-4">
                            <div class="featuredText">
                                <span class="featuredText__icon"><img src="assets/img/icon/icon-teacher.svg"></span>
                                <h3 class="featuredText__title">Giảng viên</h3>
                                <p class="featuredText__text">có nhiều năm kinh nghiệm</p>
                            </div>
                        </div>
                        <div class="col-4 col-xl-4">
                            <div class="featuredText">
                                <span class="featuredText__icon"><img src="assets/img/icon/icon-listF.svg"></span>
                                <h3 class="featuredText__title">Bài giảng</h3>
                                <p class="featuredText__text">dựa theo tài liệu mới nhất</p>
                            </div>
                        </div>
                        <div class="col-4 col-xl-4">
                            <div class="featuredText">
                                <span class="featuredText__icon"><img src="assets/img/icon/icon-platform.svg"></span>
                                <h3 class="featuredText__title">Đa nền tảng</h3>
                                <p class="featuredText__text">chỉ cần thiết bị có kết nối internet</p>
                            </div>
                        </div>
                        <div class="col-4 col-xl-4">
                            <div class="featuredText">
                                <span class="featuredText__icon"><img src="assets/img/icon/icon-24-house.svg"></span>
                                <h3 class="featuredText__title">24/7</h3>
                                <p class="featuredText__text">bất kể thời gian nào</p>
                            </div>
                        </div>
                        <div class="col-4 col-xl-4">
                            <div class="featuredText">
                                <span class="featuredText__icon"><img src="assets/img/icon/icon-payOne.svg"></span>
                                <h3 class="featuredText__title">Thanh toán 1 lần</h3>
                                <p class="featuredText__text">linh hoạt và nhanh gọn</p>
                            </div>
                        </div>
                        <div class="col-4 col-xl-4">
                            <div class="featuredText">
                                <span class="featuredText__icon"><img src="assets/img/icon/icon-saleF.svg"></span>
                                <h3 class="featuredText__title">Ưu đãi</h3>
                                <p class="featuredText__text">luôn luôn được cập nhật</p>
                            </div>
                        </div>
                        <div class="col-4 col-xl-4">
                            <div class="featuredText">
                                <span class="featuredText__icon"><img src="assets/img/icon/icon-faqF.svg"></span>
                                <h3 class="featuredText__title">Giải đáp</h3>
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
            <div class="section-testWhy__inner first" style="background-image: url(assets/img/image/bg-testimonial.jpg)">
                <div class="bg-overlay"></div>

                <div class="f-fix wow fadeInLeft">
                    <div class="title">
                        <h2 class="title__title">Cảm nhận của học viên</h2>
                    </div>

                    <div class="testimonial-slide">
                        <div class="owl-carousel">
                            <div class="testimonial">
                                <span class="testimonial__icon"><img src="assets/img/icon/icon-quote.svg" alt=""></span>
                                <p class="testimonial__quote">Học tiếng Nhất tại Tomato Online mình rất
                                    thích và muốn được giới thiếu cho nhiều người được biết đến trung tâm
                                    mình nhiều hơn, muốn mọi người biết đến nhiều hơn nữa người được biết
                                    đến trung tâm mình nhiều hơn, muốn mọi người biết đến nhiều hơn nữa</p>

                                <div class="testimonial__info">
                                    <h3 class="testimonial__name">Nguyễn Quốc Khánh</h3>
                                    <p class="testimonial__position">Học viên đang theo học</p>
                                </div>
                            </div>
                            <div class="testimonial">
                                <span class="testimonial__icon"><img src="assets/img/icon/icon-quote.svg" alt=""></span>
                                <p class="testimonial__quote">Học tiếng Nhất tại Tomato Online mình rất
                                    thích và muốn được giới thiếu cho nhiều người được biết đến trung tâm
                                    mình nhiều hơn, muốn mọi người biết đến nhiều hơn nữa người được biết
                                    đến trung tâm mình nhiều hơn, muốn mọi người biết đến nhiều hơn nữa</p>
                                <div class="testimonial__info">
                                    <h3 class="testimonial__name">Nguyễn Quốc Khánh</h3>
                                    <p class="testimonial__position">Học viên đang theo học</p>
                                </div>
                            </div>
                            <div class="testimonial">
                                <span class="testimonial__icon"><img src="assets/img/icon/icon-quote.svg" alt=""></span>
                                <p class="testimonial__quote">Học tiếng Nhất tại Tomato Online mình rất
                                    thích và muốn được giới thiếu cho nhiều người được biết đến trung tâm
                                    mình nhiều hơn, muốn mọi người biết đến nhiều hơn nữa người được biết
                                    đến trung tâm mình nhiều hơn, muốn mọi người biết đến nhiều hơn nữa</p>
                                <div class="testimonial__info">
                                    <h3 class="testimonial__name">Nguyễn Quốc Khánh</h3>
                                    <p class="testimonial__position">Học viên đang theo học</p>
                                </div>
                            </div>
                            <div class="testimonial">
                                <span class="testimonial__icon"><img src="assets/img/icon/icon-quote.svg" alt=""></span>
                                <p class="testimonial__quote">Học tiếng Nhất tại Tomato Online mình rất
                                    thích và muốn được giới thiếu cho nhiều người được biết đến trung tâm
                                    mình nhiều hơn, muốn mọi người biết đến nhiều hơn nữa người được biết
                                    đến trung tâm mình nhiều hơn, muốn mọi người biết đến nhiều hơn nữa</p>
                                <div class="testimonial__info">
                                    <h3 class="testimonial__name">Nguyễn Quốc Khánh</h3>
                                    <p class="testimonial__position">Học viên đang theo học</p>
                                </div>
                            </div>
                            <div class="testimonial">
                                <span class="testimonial__icon"><img src="assets/img/icon/icon-quote.svg" alt=""></span>
                                <p class="testimonial__quote">Học tiếng Nhất tại Tomato Online mình rất
                                    thích và muốn được giới thiếu cho nhiều người được biết đến trung tâm
                                    mình nhiều hơn, muốn mọi người biết đến nhiều hơn nữa người được biết
                                    đến trung tâm mình nhiều hơn, muốn mọi người biết đến nhiều hơn nữa</p>
                                <div class="testimonial__info">
                                    <h3 class="testimonial__name">Nguyễn Quốc Khánh</h3>
                                    <p class="testimonial__position">Học viên đang theo học</p>
                                </div>
                            </div>
                        </div>
                        <ul class="owl-dot-custom">
                            <li class="owl-dot"><span style="background-image: url(assets/img/image/avatar.png)"></span></li>
                            <li class="owl-dot"><span style="background-image: url(assets/img/image/avatar.png"></span></li>
                            <li class="owl-dot"><span style="background-image: url(assets/img/image/avatar.png)"></span></li>
                            <li class="owl-dot"><span style="background-image: url(assets/img/image/avatar.png)"></span></li>
                            <li class="owl-dot"><span style="background-image: url(assets/img/image/avatar.png)"></span></li>
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
                    <h2 class="title__title">Câu hỏi thường gặp</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div id="faq" class="accordionJs wow fadeInLeft" data-wow-delay=".2s">
                    <div class="panel">
                        <h3 class="panel__title" data-toggle="collapse" data-target="#faq1" aria-expanded="true" aria-controls="faq1">
                            Tôi không rành về vi tính, vậy tôi có học được không?
                        </h3>
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
                        <h3 class="panel__title" data-toggle="collapse" data-target="#faq2" aria-expanded="false" aria-controls="faq2">
                            Tôi cần chuẩn bị gì cho lớp học Online này?
                        </h3>
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
                        <h3 class="panel__title" data-toggle="collapse" data-target="#faq3" aria-expanded="false" aria-controls="faq3">
                            Những nội dung học online tôi có thể nhận được tài liệu bằng file word hay
                            không?
                        </h3>
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
                        <h3 class="panel__title" data-toggle="collapse" data-target="#faq4" aria-expanded="false" aria-controls="faq4">
                            Sau khi học xong có được làm bài thi thử không?
                        </h3>
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
                        <h3 class="panel__title" data-toggle="collapse" data-target="#faq5" aria-expanded="false" aria-controls="faq5">
                            Có thể học online qua Ipad hay điện thoại di động không?
                        </h3>
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
                <div class="video-demo wow fadeInRight" data-wow-delay=".2s" style="background-image: url(assets/img/image/lessonbox-2.jpg);">
                    <div class="bg-overlay"></div>
                    <div class="video-demo__btn">
                        <a href="https://www.youtube.com/watch?v=8blp3Tl770Q" data-init="magnificPopupVideo"><i class="pe-icon-play"></i> Xem thử bài
                            giảng</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="ctabox wow fadeInUp">
            <span class="ctabox__icon"><img src="assets/img/icon/icon-ctabox.svg"></span>
            <div class="ctabox__wrap">
                <div class="row">
                    <div class="col-lg-8">
                        <h2 class="ctabox__title">Đăng ký nhận tư vấn để được tham gia vào môi trường dạy và
                            học ngoại ngữ Online tốt nhất Hải Phòng</h2>
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
                    <h2 class="title__title">Độ ngũ giáo viên</h2>
                </div>
            </div>
        </div>

        <div class="team-slide-mobile wow fadeInUp" data-wow-delay=".2s">
            <div class="owl-carousel">
                <div class="item">
                    <div class="teambox">
                        <div class="teambox__img">
                            <img src="assets/img/image/teambox-1.jpg">
                        </div>

                        <div class="teambox__body">
                            <h3 class="teambox__name">Bùi Thu Hà</h3>
                            <p class="teambox__position">Giảng viên</p>
                        </div>

                        <div class="teambox__hover">
                            <h3>Bùi Thu Hà</h3>
                            <div class="desc">
                                <p>- Cô sở hữu kênh&nbsp;<strong>YOUTUBE TOP 1 về " học tiếng
                                        trung"</strong></p>
                                <p>- Trình độ HSK 6.&nbsp;Kinh nghiệm 8 năm giảng dạy. Nhiệt tình vui vẻ
                                    thân thiện với mọi người</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="teambox">
                        <div class="teambox__img">
                            <img src="assets/img/image/teambox-1.jpg">
                        </div>

                        <div class="teambox__body">
                            <h3 class="teambox__name">Bùi Thu Hà</h3>
                            <p class="teambox__position">Giảng viên</p>
                        </div>

                        <div class="teambox__hover">
                            <h3>Bùi Thu Hà</h3>
                            <div class="desc">
                                <p>- Cô sở hữu kênh&nbsp;<strong>YOUTUBE TOP 1 về " học tiếng
                                        trung"</strong></p>
                                <p>- Trình độ HSK 6.&nbsp;Kinh nghiệm 8 năm giảng dạy. Nhiệt tình vui vẻ
                                    thân thiện với mọi người</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="teambox">
                        <div class="teambox__img">
                            <img src="assets/img/image/teambox-1.jpg">
                        </div>

                        <div class="teambox__body">
                            <h3 class="teambox__name">Bùi Thu Hà</h3>
                            <p class="teambox__position">Giảng viên</p>
                        </div>

                        <div class="teambox__hover">
                            <h3>Bùi Thu Hà</h3>
                            <div class="desc">
                                <p>- Cô sở hữu kênh&nbsp;<strong>YOUTUBE TOP 1 về " học tiếng
                                        trung"</strong></p>
                                <p>- Trình độ HSK 6.&nbsp;Kinh nghiệm 8 năm giảng dạy. Nhiệt tình vui vẻ
                                    thân thiện với mọi người</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="teambox">
                        <div class="teambox__img">
                            <img src="assets/img/image/teambox-1.jpg">
                        </div>

                        <div class="teambox__body">
                            <h3 class="teambox__name">Bùi Thu Hà</h3>
                            <p class="teambox__position">Giảng viên</p>
                        </div>

                        <div class="teambox__hover">
                            <h3>Bùi Thu Hà</h3>
                            <div class="desc">
                                <p>- Cô sở hữu kênh&nbsp;<strong>YOUTUBE TOP 1 về " học tiếng
                                        trung"</strong></p>
                                <p>- Trình độ HSK 6.&nbsp;Kinh nghiệm 8 năm giảng dạy. Nhiệt tình vui vẻ
                                    thân thiện với mọi người</p>
                            </div>
                        </div>
                    </div>
                </div>
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
                    <div class="consultationForm__bg wow fadeInUp" data-wow-delay=".2s" style="background-image: url(assets/img/image/consultationForm-bg.jpg);"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
