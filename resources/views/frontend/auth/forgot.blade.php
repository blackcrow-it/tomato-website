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
                    <div class="col-md-4 col-lg-5">
                        <div class="login-page__bg" style="background-image: url('{{ asset("tomato/assets/img/image/bg-login.jpg") }}');"></div>
                    </div>
                    <div class="col-md-8 col-lg-7">
                        <div class="login-page__wrap">
                            <div class="login-page__content">
                                <div class="tabJs">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('login') }}">Đăng nhập</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('register') }}">Đăng ký</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active show" href="{{ route('forgot') }}">Quên mật khẩu</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade active show">
                                            <form class="form-input form-reg" action="{{ route('sendCodeResetPassword') }}" method="POST">
                                                @csrf
                                                <div class="padding-bottom-3x">
                                                    <div class="justify-content-center">
                                                        <div class="col-md-10">
                                                            <div class="forgot">
                                                                <h2>Quên mật khẩu?</h2>
                                                                <p>Thay đổi mật khẩu của bạn trong ba bước đơn giản. Điều này sẽ giúp bạn bảo mật mật khẩu của mình!</p>
                                                                <ol class="list-unstyled">
                                                                    <li><span class="text-primary text-medium">1. </span>Nhập địa chỉ email của bạn dưới đây.</li>
                                                                    <li><span class="text-primary text-medium">2. </span>Hệ thống của chúng tôi sẽ gửi cho bạn một liên kết tạm thời</li>
                                                                    <li><span class="text-primary text-medium">3. </span>Sử dụng liên kết để đặt lại mật khẩu của bạn</li>
                                                                </ol>
                                                            </div>
                                                            <form class="card mt-4">
                                                                <div class="card-body">
                                                                    <div class="form-group">
                                                                        @if(session()->has('success'))
                                                                            <div class="text-success">
                                                                                {{ session()->get('success') }}
                                                                            </div>
                                                                        @else
                                                                            <div class="text-danger">
                                                                                {{ session()->get('danger') }}
                                                                            </div>
                                                                        @endif
                                                                        <label for="email-for-pass">Nhập địa chỉ email của bạn</label>
                                                                        <input type="email" name="email" placeholder="Nhập địa chỉ email" class="form-control" value="{{ old('email') }}">
                                                                        @error('email')
                                                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="card-footer"> <button class="btn btn-success" type="submit">Lấy mật khẩu mới</button></div>
                                                            </form>
                                                        </div>
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

