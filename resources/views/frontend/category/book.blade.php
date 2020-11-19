@extends('frontend.category.master')

@section('category_body')
<section class="section wow">
    <div class="container">
        <div class="layout layout--left">
            <div class="row">
                <div class="col-xl-3">
                    <div class="layout-sidebar">
                        <div class="widget widget--category">
                            <h2 class="widget__title">Phân loại</h2>
                            <div class="f-scroll">
                                <ul>
                                    @foreach(get_categories(null, 'book-categories') as $c1)
                                        <li class="{{ $c1->__subcategory_count > 0 ? 'menu-has-children' : null }}">
                                            <a href="{{ $c1->url }}">{{ $c1->title }}</a>
                                            @if($c1->__subcategory_count > 0)
                                                <ul class="submenu">
                                                    @foreach(get_categories($c1->id, 'book-categories') as $c2)
                                                        <li class="">
                                                            <a href="{{ $c2->url }}">{{ $c2->title }}</a>
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
                        <div class="widget widget--relatePost d-none d-xl-block">
                            <h2 class="widget__title">Bài viết mới nhất</h2>

                            <ul>
                                @foreach (get_posts(null, null, 6) as $item)
                                    <li>
                                        <a href="{{ $item->url }}">
                                            <img src="{{ $item->thumbnail }}">
                                            <h3>{{ $item->title }}</h3>
                                            <span><i class="fa fa-clock-o"></i>{{ $item->updated_at->format('d-m-Y H:i') }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="layout-content">

                        <div class="row spacing-custom">
                            @foreach($list as $item)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="bookBox">
                                        <a href="{{ $item->url }}" class="bookBox__img">
                                            <img src="{{ $item->thumbnail }}" alt="{{ $item->title }}">
                                            @if ($item->original_price)
                                                <span class="sale">-{{ 100 - ceil($item->price / $item->original_price * 100) }}%</span>
                                            @endif
                                        </a>
                                        <div class="bookBox__body">
                                            <h3 class="bookBok__title"><a href="{{ $item->url }}">{{ $item->title }}</a></h3>
                                            <div class="bookBok__price">
                                                <ins>{{ currency($item->price) }}</ins>
                                                @if ($item->original_price)
                                                    <del>{{ currency($item->original_price) }}</del>
                                                @endif
                                            </div>
                                        </div>
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
