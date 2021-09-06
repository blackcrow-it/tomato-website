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
                            @foreach ($list as $item)
                            <div class="col-6 col-lg-4">
                                <div class="lessonbox">
                                    <div class="lessonbox__inner">
                                        <a href="{{ $item->url }}" class="lessonbox__img">
                                            <img src="{{ $item->thumbnail }}">
                                            {{-- @if($course->original_price)
                                                <span class="sale">-{{ ceil(100 - $course->price / $course->original_price * 100) }}%</span>
                                            @endif --}}
                                        </a>
                                        <div class="lessonbox__body">
                                            <div class="lessonbox__cat">
                                                <a href="giaotrinhhanngu.html">Combo 1</a>
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

                                                {{-- {{ dd($item)}} --}}
                                                    <ins>{{ currency($item->price) }}</ins>
                                                </div>
                                                <a href="{{$item->url}}" class="btn btn--sm btn--outline">Chi tiết</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <nav class="pagination text-center">
                            <ul class="pagination__list">
                                <li><a href="#"><i class="fa fa-angle-double-left"></i></a></li>
                                <li><a href="#">1</a></li>
                                <li class="current"><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                                <li><a href="#">5</a></li>
                                <li><a href="#"><i class="fa fa-angle-double-right"></i></a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
