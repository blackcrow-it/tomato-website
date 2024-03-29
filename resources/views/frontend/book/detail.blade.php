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
                            <div class="detail-images">
                                <img src="{{ $book->thumbnail }}" alt="{{ $book->title }}">
                                <div class="detail-images-preview"></div>
                            </div>
                            <ul class="owl-dot-custom owl-dots">
                                @foreach($book->detail_images ?? [] as $image)
                                    <li class="owl-dot">
                                        <img src="{{ $image }}" alt="{{ $book->title }}">
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-5">
                    <div class="product-detail__image-preview-wrapper">
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
                            <div class="mb-3">
                                <div class="sharethis-inline-share-buttons"></div>
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
                                @if (auth()->check())
                                    @include('frontend.comment.item')
                                @else
                                <div class="fb-comments" data-href="{{ $book->url }}" data-width="100%" data-numposts="10" data-order-by="reverse_time"></div>
                                @endif
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
                    @foreach($related_books as $item)
                        @include('frontend.category.book_item', [ 'book' => $item ])
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
                            {{-- <form class="consultationForm__form">
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
                            </form> --}}
                            <div id="getfly-optin-form-iframe-1632304410038"></div> <script type="text/javascript"> (function(){ var r = window.document.referrer != ""? window.document.referrer: window.location.origin; var regex = /(https?:\/\/.*?)\//g; var furl = regex.exec(r); r = furl ? furl[0] : r; var f = document.createElement("iframe"); const url_string = new URLSearchParams(window.location.search); var utm_source, utm_campaign, utm_medium, utm_content, utm_term; if((!url_string.has('utm_source') || url_string.get('utm_source') == '') && document.cookie.match(new RegExp('utm_source' + '=([^;]+)')) != null){ r+= "&" +document.cookie.match(new RegExp('utm_source' + '=([^;]+)'))[0]; } else { r+= url_string.get('utm_source') != null ? "&utm_source=" + url_string.get('utm_source') : "";} if((!url_string.has('utm_campaign') || url_string.get('utm_campaign') == '') && document.cookie.match(new RegExp('utm_campaign' + '=([^;]+)')) != null){ r+= "&" +document.cookie.match(new RegExp('utm_campaign' + '=([^;]+)'))[0]; } else { r+= url_string.get('utm_campaign') != null ? "&utm_campaign=" + url_string.get('utm_campaign') : "";} if((!url_string.has('utm_medium') || url_string.get('utm_medium') == '') && document.cookie.match(new RegExp('utm_medium' + '=([^;]+)')) != null){ r+= "&" +document.cookie.match(new RegExp('utm_medium' + '=([^;]+)'))[0]; } else { r+= url_string.get('utm_medium') != null ? "&utm_medium=" + url_string.get('utm_medium') : "";} if((!url_string.has('utm_content') || url_string.get('utm_content') == '') && document.cookie.match(new RegExp('utm_content' + '=([^;]+)')) != null){ r+= "&" +document.cookie.match(new RegExp('utm_content' + '=([^;]+)'))[0]; } else { r+= url_string.get('utm_content') != null ? "&utm_content=" + url_string.get('utm_content') : "";} if((!url_string.has('utm_term') || url_string.get('utm_term') == '') && document.cookie.match(new RegExp('utm_term' + '=([^;]+)')) != null){ r+= "&" +document.cookie.match(new RegExp('utm_term' + '=([^;]+)'))[0]; } else { r+= url_string.get('utm_term') != null ? "&utm_term=" + url_string.get('utm_term') : "";} if((!url_string.has('utm_user') || url_string.get('utm_user') == '') && document.cookie.match(new RegExp('utm_user' + '=([^;]+)')) != null){ r+= "&" +document.cookie.match(new RegExp('utm_user' + '=([^;]+)'))[0]; } else { r+= url_string.get('utm_user') != null ? "&utm_user=" + url_string.get('utm_user') : "";} if((!url_string.has('utm_account') || url_string.get('utm_account') == '') && document.cookie.match(new RegExp('utm_account' + '=([^;]+)')) != null){ r+= "&" +document.cookie.match(new RegExp('utm_account' + '=([^;]+)'))[0]; } else { r+= url_string.get('utm_account') != null ?
"&utm_account=" + url_string.get('utm_account') : "";} r+="&full_url="+encodeURIComponent(window.location.href); f.setAttribute("src", "https://tomato.getflycrm.com/api/forms/viewform/?key=w8shkJjELRuKUXebItqDJKecwtfK42jJfmp9VgkcLThAKkuO9I&referrer="+r); f.style.width = "100%";f.style.height = "700px";f.setAttribute("frameborder","0");f.setAttribute("marginheight","0"); f.setAttribute("marginwidth","0");var s = document.getElementById("getfly-optin-form-iframe-1632304410038");s.appendChild(f); })(); </script>
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
    $('.entry-detail img').css('height', 'auto');

    $('.owl-dots .owl-dot').click(function () {
        $('.owl-dots .owl-dot').removeClass('active');
        $(this).addClass('active');
        $('.detail-images img').attr('src', $(this).find('img').attr('src'));
    });

    // new Drift(document.querySelector('.detail-images img'), {
    //     sourceAttribute: 'src',
    //     paneContainer: document.querySelector('.detail-images-preview'),
    //     zoomFactor: 2,
    //     onShow: function() {
    //         $('.detail-images-preview').show();
    //     },
    //     onHide: function() {
    //         $('.detail-images-preview').hide();
    //     }
    // });

</script>
@includeWhen(auth()->check(), 'frontend.comment.script', [ 'id_object' => $book->id, 'type_object' => \App\Constants\ObjectType::BOOK ])
@endsection
