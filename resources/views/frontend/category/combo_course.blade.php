@extends('frontend.category.master')

@section('category_body')
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
                            @if ($list->count() > 0)
                                @foreach ($list as $item)
                                <div class="col-6 col-lg-4">
                                    <div class="lessonbox">
                                        <div class="lessonbox__inner">
                                            <a href="{{ $item->url }}" class="lessonbox__img">
                                                @if ($item->thumbnail)
                                                <img src="{{ $item->thumbnail }}">
                                                @else
                                                <div style="height: 160px; line-height: 160px; text-align: center; ">
                                                    <p style="vertical-align: middle; line-height: 1.5; display: inline-block;">Không có ảnh</p>
                                                </div>
                                                @endif
                                                <?php
                                                    $price_origin = 0;
                                                    foreach ($item->items as $c_course) {
                                                        $price_origin += $c_course->course->price;
                                                    }
                                                ?>
                                                @if($item->price && $price_origin != 0)
                                                    <span class="sale">-{{ ceil(100 - $item->price / $price_origin * 100) }}%</span>
                                                @endif
                                            </a>
                                            <div class="lessonbox__body">
                                                <div class="lessonbox__cat">
                                                    @if($item->category)
                                                    <div class="lessonbox__cat">
                                                        <a href="">{{ $item->category->title }}</a>
                                                    </div>
                                                    @else
                                                    <div class="lessonbox__cat">
                                                    </div>
                                                    @endif
                                                </div>
                                                <h3 class="lessonbox__title"><a href="{{$item->url}}">{{$item->title}}</a></h3>
                                                <ul class="lessonbox__info">
                                                    <li>Khoá học: {{ $item->items()->count() }} khoá</li>
                                                    <li>Đánh giá:
                                                        <span class="lessonbox__rating">
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            {{-- <i class="fa fa-star-o"></i> --}}
                                                        </span>
                                                        (5)
                                                    </li>
                                                </ul>

                                                <div class="lessonbox__footer">
                                                    <div class="lessonbox__price">

                                                        <ins>{{ currency($item->price) }}</ins>
                                                        @if ($item->price < $price_origin)
                                                            <del>{{ currency($price_origin) }}</del>
                                                        @endif
                                                    </div>
                                                    <a href="{{$item->url}}" class="btn btn--sm btn--outline">Chi tiết</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div style="font-size: 20px;">Không có combo khoá học</div>
                            @endif
                        </div>
                        {{ $list->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
