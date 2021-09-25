@extends('frontend.master')

@section('header')
    <title>Xác minh hai yếu tố (2FA)</title>
    <meta content="description" value="">
    <meta property="og:title" content="">
    <meta property="og:description" content="">
    <meta property="og:url" content="">
    <meta property="og:image" content="">
@endsection

@section('body')
<style>
    .login-page {
        background-image: url('{{ asset("tomato/assets/img/image/dang_nhap.jpg") }}');
        background-repeat: no-repeat;
        background-size: cover;
    }
    .login-page__content .nav-tabs .nav-link {
        color: #000000;
    }
    .justify-center {
        justify-content: center;
    }
    .flex {
        display: flex;
    }
    .form-control {
        -webkit-transition: none;
        transition: none;
        width: 192px!important;
        height: 32px;
        text-align: center;
        padding: 0!important;
        margin-right: 10px;
    }

    .form-control:focus {
        color: #3F4254;
        background-color: #ffffff;
        border-color: #884377;
        outline: 0;
    }

    .form-control.form-control-solid {
        background-color: #F3F6F9;
        border-color: #F3F6F9;
        color: #3F4254;
        transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .form-control.form-control-solid:active,
    .form-control.form-control-solid.active,
    .form-control.form-control-solid:focus,
    .form-control.form-control-solid.focus {
        background-color: #EBEDF3;
        border-color: #EBEDF3;
        color: #3F4254;
        transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
    }
</style>
    <section class="section p-0">
        <div class="container">
            <div class="login-page">
                <div class="row">
                    <div class="col-md-4 col-lg-5">
                        {{-- <div class="login-page__bg"
                             style="background-image: url('{{ asset("tomato/assets/img/image/bg-login.jpg") }}');"></div> --}}
                    </div>
                    <div class="col-md-8 col-lg-7">
                        <div class="login-page__wrap">
                            <div class="login-page__content">
                                <div class="tabJs">
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

                                            @if(session()->has('success'))
                                                <div class="text-success">
                                                    {{ session()->get('success') }}
                                                </div>
                                            @else
                                                <div class="text-danger">
                                                    {{ session()->get('danger') }}
                                                </div>
                                            @endif
                                            <br>

                                            <div class="mb-6 text-center">
                                                <h4>Xác minh hai yếu tố (2FA)</h4>
                                                <form action="{{route('2faVerify')}}" method="POST">
                                                    @csrf
                                                    <div id="otp" class="flex justify-center">
                                                        <input class="form-control" type="number" id="one-time-password" name="one_time_password" maxlength="6" />
                                                    </div>
                                                    <p>Nhập mã từ ứng dụng hai yếu tố trên thiết bị di động của bạn</p>
                                                    <button type="submit" class="btn btn--sm">Xác minh mã</button>
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
        </div>
    </section>
@endsection
