@extends('frontend.user.master')

@section('header')
<title>Xác thực hai yếu tố</title>
<style>
    .account-well {
        padding: 10px;
        background-color: #fafafa;
        border: 1px solid #dbdbdb;
        border-radius: 0.25rem;
    }
</style>
@endsection

@section('content')
<style>
    /* @import url('https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css'); */
    .justify-center {
        justify-content: center;
    }
    .flex {
        display: flex;
    }
    .form-control {
        -webkit-transition: none;
        transition: none;
        width: 32px!important;
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
<div class="row">
    <div class="col-sm-12">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $msg)
                        <li>{{ $msg }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                @if(is_array(session('success')))
                    <ul class="mb-0">
                        @foreach(session('success') as $msg)
                            <li>{{ $msg }}</li>
                        @endforeach
                    </ul>
                @else
                    {{ session('success') }}
                @endif
            </div>
        @endif
    </div>
    <div class="col-sm-4">
        <h4 class="gl-mt-0">
            Đăng ký Trình xác thực hai yếu tố
        </h4>
        <div>Sử dụng trình xác thực mật khẩu một lần trên thiết bị di động hoặc máy tính của bạn để bật xác thực hai yếu tố (2FA).</div>
    </div>
    <div class="col-sm-8">
        @if ($data['enable'])
        <p>Bạn đã bật xác thực hai yếu tố bằng cách sử dụng trình xác thực mật khẩu một lần. Để đăng ký một thiết bị khác, trước tiên bạn phải tắt xác thực hai yếu tố.</p>
        <p>Nhập mã OTP lấy từ ứng dụng để tắt tính năng xác thực 2 yếu tố.</p>
        <div class="mb-6 text-center">
            <form action="{{route('disable2fa')}}" method="POST">
                <div id="otp" class="flex justify-center">
                    <input class="form-control" type="number" id="first" name="first" maxlength="1" />
                    <input class="form-control" type="number" id="second" name="second" maxlength="1" />
                    <input class="form-control" type="number" id="third" name="third" maxlength="1" />
                    <input class="form-control" type="number" id="fourth" name="fourth" maxlength="1" />
                    <input class="form-control" type="number" id="fifth" name="fifth" maxlength="1" />
                    <input class="form-control" type="number" id="sixth" name="sixth" maxlength="1" />
                </div>
                @csrf
                <button type="submit" class="btn btn--sm">Tắt tính năng xác thực</button>
            </form>
        </div>
        @else
        <p>Chúng tôi đề xuất các ứng dụng xác thực như <b>Google Authenticator</b> hoặc <b>Duo Mobile</b>. Click nút dưới để tải ứng dụng.</p>
        <div style="text-align: center">
            <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank"><img src="{{ asset("images/Available_on_the_App_Store.svg") }}" alt="" style="height: 40px"></a>
            <a href="https://apps.apple.com/vn/app/google-authenticator/id388497605" target="_blank"><img src="{{ asset("images/Google_Play_Store_badge_EN.svg") }}" alt="" style="height: 40px"></a>
        </div><br/>
        <p>Vui lòng dùng ứng dụng xác thực của bạn (chẳng hạn như <b>Google Authenticator</b> hoặc <b>Duo Mobile</b>) để quét mã QR này.</p>
        <div class="row">
            <div class="col-sm-5" style="text-align: center">
                {!! $data['google2fa_img'] !!}
            </div>
            <div class="col-sm-7">
                <div class="account-well">
                    <div class="gl-mt-0 gl-mb-0">
                    * Nếu không quét được mã QR, để thêm mã theo cách thủ công, hãy cung cấp các chi tiết sau vào ứng dụng xác thực trên điện thoại của bạn.
                    </div>
                    <div class="gl-mt-0 gl-mb-0">
                    <b>Tài khoản:</b> Tomato Education ({{ $data['user']->email }})<br/>
                    <b>Khoá:</b> {{ $data['secret_key'] }}
                    </div>
                </div>
            </div>
        </div><br/>
        <div class="row">
            <div class="col-sm-12">
                <div class="mb-6 text-center">
                    <p>Nhập mã OTP từ ứng dụng</p>
                    <form action="{{route('enable2fa')}}" method="post">
                        @csrf
                        <div id="otp" class="flex justify-center">
                        <input class="form-control" type="number" id="first" name="first" maxlength="1" />
                        <input class="form-control" type="number" id="second" name="second" maxlength="1" />
                        <input class="form-control" type="number" id="third" name="third" maxlength="1" />
                        <input class="form-control" type="number" id="fourth" name="fourth" maxlength="1" />
                        <input class="form-control" type="number" id="fifth" name="fifth" maxlength="1" />
                        <input class="form-control" type="number" id="sixth" name="sixth" maxlength="1" />
                        </div>
                        <div><button class="btn btn--sm" type="submit">Đăng ký với ứng dụng xác thực</button></div>
                    </form>
                </div>
            </div>
        </div>
        {{-- <form action="{{route('generate2faSecret')}}" method="POST">
            @csrf
            <button class="btn btn--sm" type="submit">Bật tính năng</button>
        </form> --}}
        @endif
    </div>
</div>



@endsection

@section('user_script')
<script>
function OTPInput() {
  const inputs = document.querySelectorAll('#otp > *[id]');
  for (let i = 0; i < inputs.length; i++) {
    inputs[i].addEventListener('keydown', function(event) {
      if (event.key === "Backspace") {
        inputs[i].value = '';
        if (i !== 0)
          inputs[i - 1].focus();
      } else {
        if (i === inputs.length - 1 && inputs[i].value !== '') {
          return true;
        } else if (event.keyCode > 47 && event.keyCode < 58) {
          inputs[i].value = event.key;
          if (i !== inputs.length - 1)
            inputs[i + 1].focus();
          event.preventDefault();
        } else if (event.keyCode > 64 && event.keyCode < 91) {
          inputs[i].value = String.fromCharCode(event.keyCode);
          if (i !== inputs.length - 1)
            inputs[i + 1].focus();
          event.preventDefault();
        }
      }
    });
  }
}
OTPInput();
</script>
@endsection
