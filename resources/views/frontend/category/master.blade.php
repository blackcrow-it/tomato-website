@extends('frontend.master')

@section('header')
<title>{{ $category->meta_title ?? $category->title }}</title>
<meta content="description" value="{{ $category->meta_description ?? $category->description }}">
<meta property="og:title" content="{{ $category->og_title ?? $category->meta_title ?? $category->title }}">
<meta property="og:description" content="{{ $category->og_description ?? $category->meta_description ?? $category->description }}">
<meta property="og:url" content="{{ $category->url }}">
<meta property="og:image" content="{{ $category->og_image ?? $category->cover }}">
<meta property="og:type" content="website">
<link rel="canonical" href="{{ $category->url }}">
@endsection

@section('headings')
{!! $category->headings !!}
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
        <h1 class="page-title__title">{{ $category->title }}</h1>
    </div>
</section>

@yield('category_body')

<section class="section bg-gray" id="consultationForm">
    <div class="container">
        <div class="consultationForm">
            <div class="row no-gutters">
                <div class="col-md-6">
                    <div class="consultationForm__content">
                        <div class="consultationForm__fix">
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
                    <div class="consultationForm__bg" style="background-image: url(assets/img/image/consultationForm-bg.jpg);"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
