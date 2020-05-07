<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Đăng nhập | Admin Panel</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('admin.home') }}"><b>Admin</b>Panel</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                @if ($errors->any())
                    <blockquote class="quote-danger m-0 mb-3">
                        <ul class="pl-4 mb-0">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </blockquote>
                @else
                    <p class="login-box-msg">Đăng nhập để tiếp tục</p>
                @endif
                <form action="{{ route('admin.login') }}" method="post"> @csrf <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Username" name="username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-7">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">Ghi nhớ</label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-5">
                            <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-sign-in-alt"></i> Đăng nhập</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <div class="social-auth-links text-center mb-3">
                    <p>- HOẶC -</p>
                    <a href="" class="btn btn-block btn-primary" onclick="alert('Nút này không có tác dụng đâu :)'); return false;">
                        <i class="fab fa-facebook mr-2"></i> Đăng nhập bằng Facebook
                    </a>
                    <a href="" class="btn btn-block btn-danger" onclick="alert('Nút này không có tác dụng đâu :)'); return false;">
                        <i class="fab fa-google mr-2"></i> Đăng nhập bằng Google
                    </a>
                </div>
                <!-- /.social-auth-links -->
                <p class="mb-1">
                    <a href="" onclick="alert('Nút này không có tác dụng đâu :)'); return false;">Quên mật khẩu</a>
                </p>
                <p class="mb-0">
                    <a href="" onclick="alert('Nút này không có tác dụng đâu :)'); return false;">Đăng ký thành viên</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->
    <!-- jQuery -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
</body>

</html>
