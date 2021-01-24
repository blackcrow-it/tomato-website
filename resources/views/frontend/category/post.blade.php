@extends('frontend.category.master')

@section('category_body')
<section class="section wow">
    <div class="container">
        <div class="layout layout--left">
            <div class="row">
                <div class="col-xl-3">
                    <div class="layout-sidebar">
                        <div class="widget widget--category">
                            <div class="widget__title">Chuyên mục</div>
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
                            <div class="widget__title">Khoá học</div>

                            <ul>
                                @foreach(get_categories(null, 'course-categories') as $c1)
                                    <li class="item">
                                        <a href="{{ $c1->url }}">
                                            <span class="item__icon">
                                                <img src="{{ $c1->icon }}" alt="{{ $c1->title }}">
                                            </span>
                                            <div class="item__title">{{ $c1->title }}</div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="widget widget--book d-none d-xl-block">
                            <div class="widget__title">Sách</div>
                            <div class="owl-carousel">
                                @foreach($featured_books as $item)
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
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="layout-content">
                        @foreach($featured_posts as $item)
                            <div class="post-featured">
                                <a href="{{ $item->url }}" class="post-featured__img">
                                    <img src="{{ $item->cover ?? $item->thumbnail }}" alt="{{ $item->title }}">
                                </a>
                                <div class="post-featured__body">
                                    <div class="post-featured__meta">
                                        @if($item->category)
                                            <span class="meta-cat"><a href="{{ $item->category->url }}">{{ $item->category->title }}</a></span>
                                        @endif
                                        <span class="meta-date">{{ $item->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="post-featured__title">
                                        <a href="{{ $item->url }}">{{ $item->title }}</a>
                                    </div>
                                    <div>{!! $item->description !!}</div>
                                    <a href="{{ $item->url }}" class="btn-link">Xem chi tiết <i class="fa fa-angle-right"></i></a>
                                </div>
                            </div>
                        @endforeach

                        <div class="post-list">
                            <?php $featuredPostIds = $featured_posts->pluck('id'); ?>
                            @foreach($list as $item)
                                @if($featuredPostIds->contains($item->id))
                                    @continue
                                @endif
                                <div class="post-list__item">
                                    <a href="{{ $item->url }}" class="post-list__img">
                                        <img src="{{ $item->thumbnail }}" alt="{{ $item->title }}">
                                    </a>
                                    <div class="post-list__body">
                                        <div class="post-list__meta">
                                            @if($item->category)
                                                <span class="meta-cat"><a href="{{ $item->category->url }}">{{ $item->category->title }}</a></span>
                                            @endif
                                            <span class="meta-date">{{ $item->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <div class="post-list__title"><a href="{{ $item->url }}">{{ $item->title }}</a></div>
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
                    <div class="widget__title">Khoá học</div>

                    <ul>
                        @foreach(get_categories(null, 'course-categories') as $c1)
                            <li class="item">
                                <a href="{{ $c1->url }}">
                                    <span class="item__icon">
                                        <img src="{{ $c1->icon }}" alt="{{ $c1->title }}">
                                    </span>
                                    <div class="item__title">{{ $c1->title }}</div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="widget widget--book">
                    <div class="widget__title">Sách</div>
                    <div class="owl-carousel">
                        <a href="chitietsach.html" class="item">
                            <div class="item__img"><img src="assets/img/image/bookBox-1.jpg"></div>
                            <div class="item__title">Giáo trình hán ngữ</div>
                            <div class="item__price">
                                <ins>499.000đ</ins>
                                <del>899.000đ</del>
                            </div>
                        </a>
                        <a href="chitietsach.html" class="item">
                            <div class="item__img"><img src="assets/img/image/bookBox-1.jpg"></div>
                            <div class="item__title">Giáo trình hán ngữ</div>
                            <div class="item__price">
                                <ins>499.000đ</ins>
                                <del>899.000đ</del>
                            </div>
                        </a>
                        <a href="chitietsach.html" class="item">
                            <div class="item__img"><img src="assets/img/image/bookBox-1.jpg"></div>
                            <div class="item__title">Giáo trình hán ngữ</div>
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
