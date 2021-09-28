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
<style>
    @media (max-width: 1259.98px) {
        #related-book {
            display: none;
        }
    }
    /* #related-book {
        display: none;
    } */
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
        <h1 class="page-title__title">{{ $combo_course->title }}</h1>
    </div>
</section>

<section class="section wow">
    <div class="container">
        <div class="layout layout--left">
            <div class="row">
                <div class="col-xl-3">
                    <div class="layout-sidebar">
                        <div class="widget widget--lessonCat sticky-top">
                            <h2 class="widget__title">Combo Khoá học</h2>
                            @if (count(get_categories(null, 'combo-course-categories')) > 0)
                            <div class="f-scroll">
                                <ul>
                                    @foreach(get_categories(null, 'combo-course-categories') as $c1)
                                        <li class="{{ $c1->__subcategory_count > 0 ? 'menu-has-children' : null }}">
                                            <a href="{{ $c1->url }}">{{ $c1->title }}</a>
                                            @if($c1->__subcategory_count > 0)
                                                <ul class="submenu">
                                                    @foreach(get_categories($c1->id, 'combo-course-categories') as $c2)
                                                        <li class="{{ $c2->__subcategory_count > 0 ? 'menu-has-children' : null }}">
                                                            <a href="{{ $c2->url }}">{{ $c2->title }}</a>
                                                            @if($c2->__subcategory_count > 0)
                                                                <ul class="submenu">
                                                                    @foreach(get_categories($c2->id, 'combo-course-categories') as $c3)
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
                            @else
                                <p style="margin: 20px">Chưa có danh mục cho combo</p>
                            @endif
                        </div>
                        {{-- <div class="widget widget--mockTest d-none d-xl-block">
                            <h2 class="widget__title">Kiểm tra kiến thức</h2>
                            <div class="f-content">
                                <a href="thithudauvao.html" class="btn btn--secondary">Vào thi</a>
                            </div>
                        </div> --}}
                        <div class="widget widget--book" id="related-book">
                            <h2 class="widget__title">Tài liệu liên quan</h2>
                            @if (count($related_books) > 0)
                            <div class="owl-carousel">
                                @foreach($related_books as $item)
                                    <a href="{{ $item->url }}" class="item">
                                        <div class="item__img">
                                            <img src="{{ $item->thumbnail }}" alt="{{ $item->title }}">
                                        </div>
                                        <div class="item__title">{{ $item->title }}</div>
                                        <div class="item__price">
                                            <ins>{{ currency($item->price) }}</ins>
                                            @if($item->original_price)
                                                <del>{{ currency($item->original_price) }}</del>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            @else
                                <p style="margin: 20px">Chưa có tài liệu liên quan.</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="layout-content">
                        <div class="row spacing-custom lessonbox-wrap-style">
                            @if ($combo_course->items->count() > 0)
                                @foreach ($combo_course->items as $item)
                                @php
                                    $course = $item->course;
                                @endphp
                                <div class="col-6 col-lg-4">
                                    @include('frontend.category.course_item', [ 'course' => $course ])
                                </div>
                                @endforeach
                            @else
                                <div style="font-size: 20px">Không có khoá học nào trong combo này</div>
                            @endif
                        </div>

                        <div class="product-detail__detail">
                            <div class="tabJs">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#tabbuy" role="tab" aria-controls="tabbuy" aria-selected="true">Mua combo</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabgioithieu" role="tab" aria-controls="tabgioithieu" aria-selected="true">Giới thiệu</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tab-tailieu" role="tab" aria-controls="tab-tailieu" aria-selected="false">Tài liệu liên quan</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tab-binhluan" role="tab" aria-controls="tab-binhluan" aria-selected="false">Bình luận</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tab-danhgia" role="tab" aria-controls="tab-giaotrinh" aria-selected="false">Đánh giá</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade" id="tabgioithieu" role="tabpanel">
                                        <div class="entry-detail">
                                            {!! $combo_course->content !!}
                                        </div>
                                        <div class="mb-3">
                                            <div class="sharethis-inline-share-buttons"></div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade show active" id="tabbuy" role="tabpanel">
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
                                                        <td class="tongtien">
                                                            <b>{{ currency($combo_course->price) }}</b><br><br>
                                                            @if(auth()->check())
                                                            <button type="button" data-form="#add-to-cart" class="btn btn-add-to-cart {{ $added_to_cart ? 'added' : '' }}">
                                                                <span class="add-to-cart-text">Thêm vào giỏ</span>
                                                                <span class="loading-text"><i class="fa fa-opencart"></i> Đang thêm...</span>
                                                                <span class="complete-text"><i class="fa fa-check"></i> Đã thêm</span>
                                                            </button>
                                                            @else
                                                            <a href="{{ route('login') }}" class="btn">Đăng nhập</a>
                                                            <div class="product-detal__btn">
                                                                <div class="btn-min">hoặc <a href="#consultationForm" class="btn-scroll-form">Đăng ký nhận tư vấn</a></div>
                                                            </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <form action="{{ route('cart.add') }}" id="add-to-cart" class="invisible">
                                                <input type="hidden" name="object_id" value="{{ $combo_course->id }}">
                                                <input type="hidden" name="type" value="{{ \App\Constants\ObjectType::COMBO_COURSE }}">
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="tab-tailieu" role="tabpanel">
                                        @if (count($related_books) > 0)
                                        <div class="bookBook-retale">
                                            <div class="owl-carousel" data-slide-four-item>
                                                @foreach($related_books as $item)
                                                    @include('frontend.category.book_item', [ 'book' => $item ])
                                                @endforeach
                                            </div>
                                        </div>
                                        @else
                                        <div>Đang cập nhật ...</div>
                                        @endif
                                    </div>
                                    <div class="tab-pane fade" id="tab-binhluan" role="tabpanel">
                                        <div class="commentbox-wrap">
                                            @if (auth()->check())
                                                @include('frontend.comment.item')
                                            @else
                                            <div class="fb-comments" data-href="{{ $combo_course->url }}" data-width="100%" data-numposts="10" data-order-by="reverse_time"></div>
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
                                                    <p class="f-header__text">Cảm nhận của bạn về combo khoá học này?</p>
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
                                                    <div><i>Vui lòng sở hữu combo khoá học này để có thể đánh giá.</i></div>
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
                                <div class="title__title">Combo khoá học liên quan</div>
                            </div>

                            @if (count($related_combos_course) > 0)
                            <div class="lessonbox-wrap-min">
                                <div class="row">
                                    @foreach($related_combos_course as $item)
                                    <div class="col-md-6">
                                        <div class="lessonbox style-combo">
                                            <div class="lessonbox__inner">
                                                @if ($item->cover)
                                                    <div class="lessonbox__img"><img src="{{ $item->cover }}" alt="{{ $item->title }}"></div>
                                                @endif
                                                <div class="lessonbox__body">
                                                    @if($item->category)
                                                    <div class="lessonbox__cat">
                                                        <a href="">{{ $item->category->title }}</a>
                                                    </div>
                                                    @else
                                                    <div class="lessonbox__cat">
                                                    </div>
                                                    @endif
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
                                                        <div class="lessonbox__btn">
                                                            <a href="{{ $item->url }}" class="btn btn--secondary btn--sm">Mua ngay</a>
                                                            <a href="{{ $item->url }}" class="btn btn--sm btn--outline">Chi tiết</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            <div>Đang cập nhật ...</div>
                            @endif
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
                    { object_id: {{ $combo_course->id }}, type: '{{ \App\Constants\ObjectType::COMBO_COURSE }}', star: this.star, comment: this.comment }
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
                        params: { object_id: {{ $combo_course->id }}, type: '{{ \App\Constants\ObjectType::COMBO_COURSE }}', page: this.currentPage }
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
@includeWhen(auth()->check(), 'frontend.comment.script', [ 'id_object' => $combo_course->id, 'type_object' => \App\Constants\ObjectType::COMBO_COURSE ])
@endsection
