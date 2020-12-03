@extends('frontend.master')

@section('header')
<title>{{ config('settings.homepage_title') }}</title>
<meta name="keywords" content="{{ config('settings.homepage_keywords') }}">
<meta name="description" content="{{ config('settings.homepage_description') }}">
<link rel="canonical" href="{{ route('home') }}">
<meta property="og:title" content="{{ config('settings.homepage_og_title') }}">
<meta property="og:description" content="{{ config('settings.homepage_og_description') }}">
<meta property="og:url" content="{{ route('home') }}">
<meta property="og:image" content="{{ config('settings.homepage_og_image') }}">
<meta property="og:type" content="website">
@endsection

@section('headings')
{!! config('settings.homepage_headings') !!}
@endsection

@section('body')
<section class="section page-title">
    <div class="container">
        <nav class="breadcrumb-nav">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            </ol>
        </nav>
        <h1 class="page-title__title">Liên hệ</h1>
    </div>
</section>

<section class="section section-contact" id="consultationForm">
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
        <div class="row">
            <div class="col-md-6">
                <div class="item-first">
                    <div class="title">
                        <h2 class="title__title">Gửi lời nhắn</h2>
                    </div>
                    <form class="fom-contact">
                        <div class="input-item">
                            <div class="input-item__inner">
                                <input type="text" name="name" placeholder="Họ và tên" class="form-control">
                            </div>
                        </div>
                        <div class="input-item">
                            <div class="input-item__inner">
                                <input type="text" name="email" placeholder="Email *" class="form-control">
                            </div>
                        </div>
                        <div class="input-item">
                            <div class="input-item__inner">
                                <input type="text" name="phone" placeholder="Số điện thoại *" class="form-control">
                            </div>
                        </div>
                        <div class="input-item">
                            <div class="input-item__inner">
                                <textarea type="text" name="content" placeholder="Nội dung" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="button-item">
                            <button type="submit" class="btn btn--secondary">Gửi</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="item-last">
                    <div class="title">
                        <h2 class="title__title">Công Ty Cổ Tư Vấn Và Đào Tạo TOMATO</h2>
                    </div>

                    <ul class="contact-inner__list">
                        <li><b>Địa chỉ ĐKKD CS1</b> Số 300 Lạch Tray, Quận. Lê Chân, Tp. Hải Phòng</li>
                        <li><b>Cơ sở 3:</b>Số 65 Quán Nam, Quận, Lê Chân, Tp. Hải Phòng</li>
                        <li><b>Cơ sở 4:</b>408 Trường Sơn, An Lão, Tp. Hải Phòng</li>
                        <li><b>Điện thoại:</b>0225 657 2222 - 0225 628 0123 <br>Support: (Zalo) 0965 113 913 Mr Nam</li>
                        <li><b>Hotline:</b>0964 299 222</li>
                        <li><b>Email:</b>ngoaingutomatohp@gmail.com</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section section-map">
    <div class="google-map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d29831.21331797907!2d106.69613!3d20.835677!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xc2116c9161a6035f!2zVHJ1bmcgdMOibSBuZ2_huqFpIG5n4buvIEjhuqNpIHBow7JuZyBUb21hdG8!5e0!3m2!1svi!2sus!4v1590414900602!5m2!1svi!2sus" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
    </div>
</section>
@endsection
