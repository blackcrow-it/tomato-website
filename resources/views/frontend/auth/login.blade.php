@extends('frontend.master')

@section('header')
<title>Đăng nhập</title>
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
                <div class="col-md-4 col-lg-6">
                    <div class="login-page__bg" style="background-image: url('{{ asset("tomato/assets/img/image/bg-login.jpg") }}');"></div>
                </div>
                <div class="col-md-8 col-lg-6">
                    <div class="login-page__wrap">
                        <div class="login-page__content">
                            <div class="tabJs">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active show" href="{{ route('login') }}">Đăng nhập</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">Đăng ký</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('forgot') }}">Quên mật khẩu</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade active show">
                                        @if($errors->any())
                                            <div class="alert alert-primary" role="alert">
                                                <ul class="pl-4 mb-0">
                                                    @foreach($errors->all() as $err)
                                                        <li>{{ $err }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        <form class="form-input form-login" action="{{ route('login') }}" method="POST">
                                            @csrf
                                            <div class="input-item">
                                                <div class="input-item__inner">
                                                    <input type="text" name="username" placeholder="Tên đăng nhập" class="form-control">
                                                </div>
                                            </div>
                                            <div class="input-item">
                                                <div class="input-item__inner">
                                                    <input type="password" name="password" placeholder="Mật khẩu" class="form-control">
                                                </div>
                                            </div>
                                            <div class="input-item">
                                                <label class="checkbox-item">
                                                    <input type="checkbox" name="remember">
                                                    <span class="checkbox-item__check"></span>
                                                    <p class="checkbox-item__text">Ghi nhớ đăng nhập</p>
                                                </label>

                                                <label class="checkbox-item">
                                                    <a href="{{ route('forgot') }}" class="text-primary">Quên mật khẩu ?</a>
                                                </label>
                                            </div>

                                            <div class="button-item text-right">
                                                <button type="submit" class="btn btn--block">Đăng nhập</button>
                                            </div>

                                            <div class="login-social">
                                                <span>hoặc đăng nhập bằng</span>
                                                <div class="login-social__btn">
                                                    <a href="{{ route('auth.facebook') }}" class="login-facebook"><i class="fa fa-facebook"></i> Facebook</a>
                                                    <a href="{{ route('auth.google') }}" class="login-google"><i class="fa fa-google-plus"></i> Google</a>
                                                </div>
                                            </div>

                                            <div class="login-social">
                                                <span>hoặc</span>
                                                <div class="login-social__btn">
                                                    <a href="{{ route('register') }}" class="register"><i class="fa fa-sign-in"></i> Tạo tài khoản</a>
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
