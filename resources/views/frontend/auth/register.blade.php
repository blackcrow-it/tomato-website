@extends('frontend.master')

@section('header')
<title>Đăng ký</title>
<meta content="description" value="">
<meta property="og:title" content="">
<meta property="og:description" content="">
<meta property="og:url" content="">
<meta property="og:image" content="">
@endsection

@section('body')
<section class="section p-0">
    <div class="container">
        <div class="login-page">
            <div class="row">
                <div class="col-md-5 col-lg-6">
                    <div class="login-page__bg" style="background-image: url('{{ asset("tomato/assets/img/image/bg-login.jpg") }}');"></div>
                </div>
                <div class="col-md-7 col-lg-6">
                    <div class="login-page__wrap">
                        <div class="login-page__content">
                            <div class="tabJs">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">Đăng nhập</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active show" href="{{ route('register') }}">Đăng ký</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade active show">
                                        <form class="form-input form-reg" action="{{ route('register') }}" method="POST">
                                            @csrf
                                            <div class="input-item">
                                                <div class="input-item__inner">
                                                    <input type="text" name="username" placeholder="Tên đăng nhập (bắt buộc)" class="form-control" value="{{ old('username') }}">
                                                    @error('username')
                                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="input-item">
                                                <div class="input-item__inner">
                                                    <input type="password" name="password" placeholder="Mật khẩu (bắt buộc)" class="form-control">
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="input-item">
                                                <div class="input-item__inner">
                                                    <input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu (bắt buộc)" class="form-control">
                                                    @error('password_confirmation')
                                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="input-item">
                                                <div class="input-item__inner">
                                                    <input type="text" name="name" placeholder="Họ và tên" class="form-control" value="{{ old('name') }}">
                                                    @error('name')
                                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="input-item">
                                                <div class="input-item__inner">
                                                    <input type="email" name="email" placeholder="Email" class="form-control" value="{{ old('email') }}">
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="input-item">
                                                <div class="input-item__inner">
                                                    <input type="text" name="phone" placeholder="Số điện thoại" class="form-control" value="{{ old('phone') }}">
                                                    @error('phone')
                                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="input-item">
                                                <label class="checkbox-item">
                                                    <input type="checkbox" name="checkbox_requirement">
                                                    <span class="checkbox-item__check"></span>
                                                    <p class="checkbox-item__text">Đồng ý với các điều khoản sử dụng và chính sách bảo mật của Tomato Online</p>
                                                </label>
                                                @error('checkbox_requirement')
                                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                            <div class="button-item text-right">
                                                <button type="submit" class="btn btn--secondary btn--block">Đăng ký</button>
                                            </div>

                                            <div class="login-social">
                                                <span>hoặc đăng nhập bằng</span>
                                                <div class="login-social__btn">
                                                    <a href="" class="login-facebook" onclick="alert('Đang trong quá trình xây dựng'); return false;"><i class="fa fa-facebook"></i> Facebook</a>
                                                    <a href="{{ route('auth.google') }}" class="login-google"><i class="fa fa-google-plus"></i> Google</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
