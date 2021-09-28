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
        background-color: #77af41;
        padding: 7px 11px 6px;
        margin: 0 2px 2px 0;
    }
    .page-combo-course a:hover {
        background-color: #e71d36;
    }
    .page-combo-course a.btn {
        line-height: normal;
        height: auto;
        border-radius: 0px;
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
            @foreach($related_combos_course as $item)
            <a class="btn" href="{{$item->url}}" title="{{count($item->items)}} khoá ({{currency($item->price)}})">{{$item->title}}</a>
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
                            <a class="nav-link" data-toggle="tab" href="#tab-danhgia" role="tab" aria-controls="tab-giaotrinh" aria-selected="false">Đánh giá khoá học</a>
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
                        </div>

                        <div class="tab-pane fade" id="tab-binhluan" role="tabpanel">
                            <div class="commentbox-wrap">
                                @if (auth()->check())
                                    @include('frontend.comment.item')
                                @else
                                <div class="fb-comments" data-href="{{ $course->url }}" data-width="100%" data-numposts="10" data-order-by="reverse_time"></div>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-danhgia" role="tabpanel">
                            <div class="courses-review">
                                <h3 class="title-fz-22">Điểm đánh giá</h3>
                                <div class="r-header">
                                    <div class="r-point">
                                        <div class="r-point__inner">
                                            <h3 class="r-1">@{{ avgStar }}</h3>
                                            <h4 class="r-2">Course rating</h4>
                                            <p class="r-3">
                                                <i class="fa fa-star" v-if="avgStar >= 0.5"></i>
                                                <i class="fa fa-star-o" v-else></i>
                                                <i class="fa fa-star" v-if="avgStar >= 1.5"></i>
                                                <i class="fa fa-star-o" v-else></i>
                                                <i class="fa fa-star" v-if="avgStar >= 2.5"></i>
                                                <i class="fa fa-star-o" v-else></i>
                                                <i class="fa fa-star" v-if="avgStar >= 3.5"></i>
                                                <i class="fa fa-star-o" v-else></i>
                                                <i class="fa fa-star" v-if="avgStar >= 4.5"></i>
                                                <i class="fa fa-star-o" v-else></i>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="r-progress">
                                        <ul class="r-progress__ul">
                                            <li class="r-progress__li">
                                                <div class="r-progress__step">
                                                    <span v-bind:style="{ width: 100 * (countStarFive / totalRating) + '%' }"></span>
                                                </div>
                                                <p class="r-progress__star">
                                                    <span>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                    </span>
                                                    @{{ countStarFive }}
                                                </p>
                                            </li>
                                            <li class="r-progress__li">
                                                <div class="r-progress__step">
                                                    <span v-bind:style="{ width: 100 * (countStarFour / totalRating) + '%' }"></span>
                                                </div>
                                                <p class="r-progress__star">
                                                    <span>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star-o"></i>
                                                    </span>
                                                    @{{countStarFour}}
                                                </p>
                                            </li>
                                            <li class="r-progress__li">
                                                <div class="r-progress__step">
                                                    <span v-bind:style="{ width: 100 * (countStarThree / totalRating) + '%' }"></span>
                                                </div>
                                                <p class="r-progress__star">
                                                    <span>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star-o"></i>
                                                        <i class="fa fa-star-o"></i>
                                                    </span>
                                                    @{{countStarThree}}
                                                </p>
                                            </li>
                                            <li class="r-progress__li">
                                                <div class="r-progress__step">
                                                    <span v-bind:style="{ width: 100 * (countStarTwo / totalRating) + '%' }"></span>
                                                </div>
                                                <p class="r-progress__star">
                                                    <span>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star-o"></i>
                                                        <i class="fa fa-star-o"></i>
                                                        <i class="fa fa-star-o"></i>
                                                    </span>
                                                    @{{countStarTwo}}
                                                </p>
                                            </li>
                                            <li class="r-progress__li">
                                                <div class="r-progress__step">
                                                    <span v-bind:style="{ width: 100 * (countStarOne / totalRating) + '%' }"></span>
                                                </div>
                                                <p class="r-progress__star">
                                                    <span>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star-o"></i>
                                                        <i class="fa fa-star-o"></i>
                                                        <i class="fa fa-star-o"></i>
                                                        <i class="fa fa-star-o"></i>
                                                    </span>
                                                    @{{countStarOne}}
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="commentbox">
                                    <div class="commentList">
                                        <ul class="commentList__item">
                                            <li v-for="(item, index) in listRating" :key="item.id" v-if="item.visible || 'true' == @if(Gate::check('admin') || Gate::check('course.edit'))'true'@else'false'@endif">
                                                <div class="commentList__inner">
                                                    <div class="commentList__avatar">
                                                        <img :src="item.user.avatar" alt="">
                                                    </div>
                                                    <div class="commentList__body">
                                                        <h3 class="commentList__name">@{{ item.user.name }}</h3>
                                                        <p style="color: rgb(0, 171, 86);"><i class="fa fa-check-circle-o" aria-hidden="true"></i> Đã mua khoá học</p>
                                                        <p class="commentList__text">@{{ item.comment }}</p>
                                                        <div class="commentList__meta">
                                                            <span class="meta-date"><i class="fa fa-clock-o"></i>@{{ datetimeFormat(item.updated_at) }}</span>
                                                        </div>
                                                        @if(Gate::check('admin') || Gate::check('course.edit'))
                                                        <div>
                                                            <br/>
                                                            <button @click="toggleVisible(item.id, index)" class="btn--sm" :disabled="loadingToggle">
                                                                <span v-if="item.visible">Ẩn</span>
                                                                <span v-else>Hiện</span>
                                                            </button>
                                                        </div>
                                                        @endif
                                                        <div class="commentList__star">
                                                            <i class="fa fa-star" v-if="item.star >= 1"></i>
                                                            <i class="fa fa-star-o" v-else></i>
                                                            <i class="fa fa-star" v-if="item.star >= 2"></i>
                                                            <i class="fa fa-star-o" v-else></i>
                                                            <i class="fa fa-star" v-if="item.star >= 3"></i>
                                                            <i class="fa fa-star-o" v-else></i>
                                                            <i class="fa fa-star" v-if="item.star >= 4"></i>
                                                            <i class="fa fa-star-o" v-else></i>
                                                            <i class="fa fa-star" v-if="item.star >= 5"></i>
                                                            <i class="fa fa-star-o" v-else></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div style="text-align: center" v-if="loadMore">
                                    <button class="btn btn--sm btn--outline" @click="getData">
                                        <img src="/tomato/assets/img/icon/icon-loading.gif" v-if="loadingMore">
                                        <span v-else>Tải thêm đánh giá</span>
                                    </button>
                                </div>

                                <div class="addReviewBox">
                                    <div class="f-header">
                                        <h3 class="title-fz-22">Thêm đánh giá</h3>
                                        <p class="f-header__text">Cảm nhận của bạn về khoá học này?</p>
                                        <div class="f-header__star">
                                            <input id="radio1" type="radio" name="star" value="5" v-model="star">
                                            <label for="radio1"></label>
                                            <input id="radio2" type="radio" name="star" value="4" v-model="star">
                                            <label for="radio2"></label>
                                            <input id="radio3" type="radio" name="star" value="3" v-model="star">
                                            <label for="radio3"></label>
                                            <input id="radio4" type="radio" name="star" value="2" v-model="star">
                                            <label for="radio4"></label>
                                            <input id="radio5" type="radio" name="star" value="1" v-model="star">
                                            <label for="radio5"></label>
                                        </div>
                                    </div>
                                    @if(auth()->check())
                                        @if($is_owned)
                                        <form class="form-wrap">
                                            <div class="input-item">
                                                <label>Nội dung</label>
                                                <div class="input-item__inner">
                                                    <textarea type="text" name="comment" class="form-control" v-model="comment"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <button type="button" class="btn" @click="sendRating" id="sendRating">Gửi đánh giá</button>
                                            </div>
                                        </form>
                                        @else
                                        <div><i>Vui lòng sở hữu khoá học để có thể đánh giá khoá học này.</i></div>
                                        @endif
                                    @else
                                    <br/>
                                        <a href="{{ route('login') }}" class="btn">Đăng nhập để đánh giá</a>
                                    @endif
                                </div>
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
<script>
    const vueCourse = new Vue({
        el: '#tab-danhgia',
        data:{
            star: 5,
            comment: '',
            listRating: [],
            avgStar: 0,
            countStarOne: 0,
            countStarTwo: 0,
            countStarThree: 0,
            countStarFour: 0,
            countStarFive: 0,
            currentPage: 1,
            loadMore: true,
            loadingMore: false,
            totalRating: 0,
            loadingToggle: false,
        },
        mounted() {
            this.getData();
        },
        methods: {
            sendRating() {
                $("#sendRating").attr("disabled", true);
                axios.post(
                    "{{ route('api.rating.add') }}",
                    { object_id: {{ $course->id }}, type: '{{ \App\Constants\ObjectType::COURSE }}', star: this.star, comment: this.comment }
                ).then(() => {
                    $("#sendRating").text("Đánh giá đã được gửi");
                    this.comment = '';
                    this.currentPage = 1;
                    this.listRating = [];
                }).then(() => {
                    this.getData();
                }).catch(() => {
                    $("#sendRating").attr("disabled", false);
                    $("#sendRating").text("Đánh giá chưa được gửi");
                });
            },
            getData() {
                this.loadingMore = true;
                axios.get(
                    "{{ route('api.rating.getAll') }}",
                    {
                        params: { object_id: {{ $course->id }}, type: '{{ \App\Constants\ObjectType::COURSE }}', page: this.currentPage }
                    }
                ).then(response => {
                    this.countStarOne = response.rank.starOne;
                    this.countStarTwo = response.rank.starTwo;
                    this.countStarThree = response.rank.starThree;
                    this.countStarFour = response.rank.starFour;
                    this.countStarFive = response.rank.starFive;
                    this.avgStar = response.avgStar;
                    this.totalRating = response.data.total;
                    response.data.data.forEach(element => {
                        this.listRating.push(element);
                    });
                    if (response.data.last_page > this.currentPage) {
                        this.currentPage += 1;
                        this.loadMore = true;
                    } else {
                        this.loadMore = false;
                    }
                }).finally(() => {
                    this.loadingMore = false;
                });
            },
            datetimeFormat(str) {
                return moment(str).format('YYYY-MM-DD HH:mm:ss');
            },
            toggleVisible(id, index) {
                this.loadingToggle = true;
                axios.patch(
                    "api/rating/toggle/" + id,
                ).then(res => {
                    if(res.status == 'success') {
                        this.listRating[index].visible = res.data.visible;
                    }
                }).finally(() => {
                    this.loadingToggle = false;
                })
            }
        },
    });
</script>
@includeWhen(auth()->check(), 'frontend.comment.script', [ 'id_object' => $course->id, 'type_object' => \App\Constants\ObjectType::COURSE ])
@endsection
