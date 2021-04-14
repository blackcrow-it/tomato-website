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
                                    <div class="tab-content">
                                        <div class="tab-pane fade active show">
                                            <form class="form-input form-reg" action="{{ route('saveResetPassword') }}" method="POST">
                                                @csrf
                                                <div class="padding-bottom-3x">
                                                    <div class="justify-content-center">
                                                        <div class="col-md-10">
                                                            <form class="card mt-4">
                                                                <div class="card-body">
                                                                    <h4>Form Thay đổi</h4>
                                                                    <input type="hidden" name="email" value="{{ $email }}" class="form-control">
                                                                    <input type="hidden" name="code" value="{{ $code }}" class="form-control">

                                                                    <div class="form-group">
                                                                        <label for="email-for-pass">Nhập mật khẩu : </label>
                                                                        <input type="password" name="password" placeholder="Nhập mật khẩu" class="form-control">
                                                                        @error('password')
                                                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="email-for-pass">Xác nhận lại mật khẩu : </label>
                                                                        <input type="password" name="password_confirm" placeholder="Xác nhận lại mật khẩu" class="form-control">
                                                                        @error('password_confirm')
                                                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="card-footer"> <button class="btn btn-success" type="submit">Thay đổi mật khẩu</button></div>
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
