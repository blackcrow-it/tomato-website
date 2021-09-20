@extends('frontend.user.master')

@section('header')
<title>Thay đổi mật khẩu</title>
@endsection

@section('content')
<div class="user-page__title">Thay đổi mật khẩu</div>

<div class="form-reset-password">
    <div class="row">
        <div class="col-md-8 offset-md-2 col-xl-6 offset-xl-3">
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

            @if(!session('success'))
            <form class="form-input" action="" method="POST">
                @csrf
                <div class="input-item">
                    <div class="input-item__inner">
                        <input type="password" name="old_pass" placeholder="Mật khẩu cũ" class="form-control @error('old_pass') is-invalid @enderror" required>
                    </div>
                    @error('old_pass')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="input-item">
                    <div class="input-item__inner">
                        <input type="password" name="new_pass" placeholder="Mật khẩu mới" class="form-control @error('new_pass') is-invalid @enderror" required>
                    </div>
                    @error('new_pass')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="input-item">
                    <div class="input-item__inner">
                        <input type="password" name="new_pass_confirmation" placeholder="Nhập lại mật khẩu mới" class="form-control" required>
                    </div>
                </div>
                <div class="button-item text-center text-xl-left">
                    <button type="submit" class="btn">Thay đổi mật khẩu</button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
