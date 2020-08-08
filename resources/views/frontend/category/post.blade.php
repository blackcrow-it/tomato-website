@extends('frontend.category.master')

@section('category_body')
<section class="section wow">
    <div class="container">
        <div class="layout layout--left">
            <div class="row">
                <div class="col-xl-3">
                    <div class="layout-sidebar">
                        <div class="widget widget--category">
                            <h2 class="widget__title">Chuyên mục</h2>
                            <div class="f-scroll">
                                <ul>
                                    @foreach(get_categories(null, 'post-categories') as $c1)
                                        <li class="{{ $c1->__subcategory_count > 0 ? 'menu-has-children' : null }}">
                                            <a href="{{ $c1->url }}">{{ $c1->title }}</a>
                                            @if($c1->__subcategory_count > 0)
                                                <ul class="submenu">
                                                    @foreach(get_categories($c1->id, 'post-categories') as $c2)
                                                        <li class="{{ $c2->__subcategory_count > 0 ? 'menu-has-children' : null }}">
                                                            <a href="{{ $c2->url }}">{{ $c2->title }}</a>
                                                            @if($c2->__subcategory_count > 0)
                                                                <ul class="submenu">
                                                                    @foreach(get_categories($c2->id, 'post-categories') as $c3)
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
                        </div>
                        <div class="widget widget--course d-none d-xl-block">
                            <h2 class="widget__title">Khoá học</h2>

                            <ul>
                                @foreach(get_categories(null, 'course-categories') as $c1)
                                    <li class="item">
                                        <a href="{{ $c1->url }}">
                                            <span class="item__icon">
                                                <img src="{{ $c1->icon }}" alt="{{ $c1->title }}">
                                            </span>
                                            <h3 class="item__title">{{ $c1->title }}</h3>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="widget widget--book d-none d-xl-block">
                            <h2 class="widget__title">Sách</h2>
                            <div class="owl-carousel">
                                <a href="chitietsach.html" class="item">
                                    <div class="item__img"><img src="assets/img/image/bookBox-1.jpg"></div>
                                    <h3 class="item__title">Giáo trình hán ngữ</h3>
                                    <div class="item__price">
                                        <ins>499.000đ</ins>
                                        <del>899.000đ</del>
                                    </div>
                                </a>
                                <a href="chitietsach.html" class="item">
                                    <div class="item__img"><img src="assets/img/image/bookBox-1.jpg"></div>
                                    <h3 class="item__title">Giáo trình hán ngữ</h3>
                                    <div class="item__price">
                                        <ins>499.000đ</ins>
                                        <del>899.000đ</del>
                                    </div>
                                </a>
                                <a href="chitietsach.html" class="item">
                                    <div class="item__img"><img src="assets/img/image/bookBox-1.jpg"></div>
                                    <h3 class="item__title">Giáo trình hán ngữ</h3>
                                    <div class="item__price">
                                        <ins>499.000đ</ins>
                                        <del>899.000đ</del>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="layout-content">
                        @foreach($featured_posts as $item)
                            <div class="post-featured">
                                <a href="{{ $item->url }}" class="post-featured__img">
                                    <img src="{{ $item->cover }}" alt="{{ $item->title }}">
                                </a>
                                <div class="post-featured__body">
                                    <div class="post-featured__meta">
                                        <span class="meta-cat"><a href="{{ $item->category->url }}">{{ $item->category->title }}</a></span>
                                        <span class="meta-date">{{ $item->updated_at->format('d/m/Y') }}</span>
                                    </div>
                                    <h3 class="post-featured__title">
                                        <a href="{{ $item->url }}">{{ $item->title }}</a>
                                    </h3>
                                    <p class="post-featured__text">{{ $item->description }}</p>
                                    <a href="{{ $item->url }}" class="btn-link">Xem chi tiết <i class="fa fa-angle-right"></i></a>
                                </div>
                            </div>
                        @endforeach

                        <div class="post-list">
                            @foreach($list as $item)
                                <div class="post-list__item">
                                    <a href="{{ $item->url }}" class="post-list__img">
                                        <img src="{{ $item->thumbnail }}" alt="{{ $item->title }}">
                                    </a>
                                    <div class="post-list__body">
                                        <div class="post-list__meta">
                                            <span class="meta-cat"><a href="{{ $item->category->url }}">{{ $item->category->title }}</a></span>
                                        <span class="meta-date">{{ $item->updated_at->format('d/m/Y') }}</span>
                                        </div>
                                        <h3 class="post-list__title"><a href="{{ $item->url }}">{{ $item->title }}</a></h3>
                                        <p class="post-list__text">{{ $item->description }}</p>
                                        <a href="{{ $item->url }}" class="btn-link">Xem chi tiết <i class="fa fa-angle-right"></i></a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{ $list->withQueryString()->links() }}
                    </div>
                </div>
            </div>

            <div class="layout-mobile-last d-xl-none">
                <div class="widget widget--course">
                    <h2 class="widget__title">Khoá học</h2>

                    <ul>
                        <li class="item">
                            <a href="#">
                                <span class="item__icon"><img src="assets/img/icon/icon-china.svg"></span>
                                <h3 class="item__title">Khoá học tiếng Trung</h3>
                            </a>
                        </li>
                        <li class="item">
                            <a href="#">
                                <span class="item__icon"><img src="assets/img/icon/icon-korea.svg"></span>
                                <h3 class="item__title">Khoá học tiếng Hàn</h3>
                            </a>
                        </li>
                        <li class="item">
                            <a href="#">
                                <span class="item__icon"><img src="assets/img/icon/icon-japan.svg"></span>
                                <h3 class="item__title">Khoá học tiếng Nhật</h3>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="widget widget--book">
                    <h2 class="widget__title">Sách</h2>
                    <div class="owl-carousel">
                        <a href="chitietsach.html" class="item">
                            <div class="item__img"><img src="assets/img/image/bookBox-1.jpg"></div>
                            <h3 class="item__title">Giáo trình hán ngữ</h3>
                            <div class="item__price">
                                <ins>499.000đ</ins>
                                <del>899.000đ</del>
                            </div>
                        </a>
                        <a href="chitietsach.html" class="item">
                            <div class="item__img"><img src="assets/img/image/bookBox-1.jpg"></div>
                            <h3 class="item__title">Giáo trình hán ngữ</h3>
                            <div class="item__price">
                                <ins>499.000đ</ins>
                                <del>899.000đ</del>
                            </div>
                        </a>
                        <a href="chitietsach.html" class="item">
                            <div class="item__img"><img src="assets/img/image/bookBox-1.jpg"></div>
                            <h3 class="item__title">Giáo trình hán ngữ</h3>
                            <div class="item__price">
                                <ins>499.000đ</ins>
                                <del>899.000đ</del>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
