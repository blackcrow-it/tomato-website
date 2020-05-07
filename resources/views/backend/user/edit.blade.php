@extends('backend.master')

@section('title')
    @if (request()->routeIs('admin.user.add'))
        Thêm thành viên mới
    @else
        Sửa thông tin thành viên : {{ $data->username }}
    @endif
@endsection

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">
            @if (request()->routeIs('admin.user.add'))
                Thêm thành viên mới
            @else
                Sửa thông tin thành viên : {{ $data->username }}
            @endif
        </h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.user.list') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
        </div>
    </div><!-- /.col -->
</div>
@endsection

@section('content')
@if ($errors->any())
    <div class="callout callout-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $msg)
                <li>{{ $msg }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="callout callout-success">
        @if (is_array(session('success')))
            <ul class="mb-0">
                @foreach (session('success') as $msg)
                    <li>{{ $msg }}</li>
                @endforeach
            </ul>
        @else
            {{ session('success') }}
        @endif
    </div>
@endif

<div class="card">
    <form action="" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Username" value="{{ $data->username ?? old('username') }}" class="form-control @error('username') is-invalid @enderror">
                @error('username')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Email" value="{{ $data->email ?? old('email') }}" class="form-control @error('email') is-invalid @enderror">
                @error('email')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" placeholder="Your name" value="{{ $data->name ?? old('name') }}" class="form-control">
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="input-group @error('password') is-invalid @enderror">
                    <input type="text" class="form-control" name="password" placeholder="Password" id="js-password-input">
                    <div class="input-group-append">
                        <button class="btn btn-success" type="button" id="js-generate-password"><i class="fas fa-key"></i> Generate</button>
                    </div>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary"><i class="fas fa-save"></i> Lưu</button>
        </div>
    </form>
</div>
@endsection

@section('script')
<script>
    $('#js-generate-password').click(function() {
        var password = '';
        var passwordLength = 10;
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for ( var i = 0; i < passwordLength; i++ ) {
            password += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        $('#js-password-input').val(password);

        $('#js-password-input').select();
        document.execCommand('copy');
    });
</script>
@endsection
