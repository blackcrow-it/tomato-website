<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title') | Admin Panel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- User style -->
    <link rel="stylesheet" href="{{ mix('css/backend.css') }}">
    @yield('style')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Homepage -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}" target="blank">
                        <i class="fas fa-home"></i>
                    </a>
                </li>
                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-comments"></i>
                        <span class="badge badge-danger navbar-badge">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="{{ asset('adminlte/dist/img/user1-128x128.jpg') }}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title"> Brad Diesel <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">Call me whenever you can...</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="{{ asset('adminlte/dist/img/user8-128x128.jpg') }}" alt="User Avatar" class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title"> John Pierce <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">I got your message bro</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="{{ asset('adminlte/dist/img/user3-128x128.jpg') }}" alt="User Avatar" class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title"> Nora Silvester <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">The subject goes here</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                    </div>
                </li>
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">15</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports <span class="float-right text-muted text-sm">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>
                <!-- User Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-user"></i> {{ auth()->user()->name ?? auth()->user()->username }} </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-lock mr-2"></i> Đổi mật khẩu</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item" onclick="$('#js-logout-form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất</span>
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->
        <!-- Logout Form -->
        <form action="{{ route('admin.logout') }}" method="POST" id="js-logout-form">
            @csrf
        </form>
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('admin.home') }}" class="brand-link">
                @if (auth()->user()->avatar)
                    <img src="{{ auth()->user()->avatar }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3">
                @endif
                <span class="brand-text font-weight-light">Admin Panel</span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        @include('backend.sidebar')
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    @yield('content-header')
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    @yield('content')
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline"> mrcyclo.com </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2014-2019 <a href="https://adminlte.io" target="blank">AdminLTE.io</a>.</strong> All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- User Script -->
    <script src="{{ mix('js/backend.js') }}"></script>
    <!-- CKEditor -->
    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <!-- CKFinder -->
    @include('ckfinder::setup')

    <script>
        var __imask = [];

        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip({
                trigger: 'hover',
                boundary: 'viewport'
            });

            $('[data-toggle="popover"]').popover({
                trigger: 'hover',
                boundary: 'viewport'
            });

            bsCustomFileInput.init();

            $('.editor').each(function () {
                var id = Math.random().toString(36).substring(7);
                $(this).attr('id', id);
                CKEDITOR.replace(id, {
                    filebrowserBrowseUrl: '{{ route('ckfinder_browser') }}?type=Files',
                    filebrowserUploadUrl: '{{ route('ckfinder_connector') }}?command=QuickUpload&type=Files'
                });
            });

            $('.currency').each(function() {
                var id = Math.random().toString(36).substring(7);
                __imask[id] = IMask(this, {
                    mask: Number,
                    thousandsSeparator: ' ',
                    signed: false,
                    scale: 0
                });
                $(this).data('imask', id);
            });

            $('.custom-order').each(function() {
                var id = Math.random().toString(36).substring(7);
                __imask[id] = IMask(this, {
                    mask: Number,
                    thousandsSeparator: '',
                    signed: false,
                    scale: 0
                });
                $(this).data('imask', id);
            });
        });

        $('form').submit(function() {
            $(this).find('.currency').each(function() {
                var id = $(this).data('imask');
                var unmaskedValue = __imask[id].unmaskedValue;
                __imask[id].destroy();
                __imask[id] = undefined;
                $(this).val(unmaskedValue);
            });
        });

        function selectFileWithCKFinder(inputId, previewId, type) {
            CKFinder.modal({
                chooseFiles: true,
                resourceType: type != undefined ? type : 'Files',
                width: 800,
                height: 600,
                onInit: function (finder) {
                    finder.on('files:choose', function (evt) {
                        var file = evt.data.files.first();
                        var url = file.getUrl();
                        $('#' + inputId).val(url);
                        $('#' + previewId).attr('src', url);
                    });

                    finder.on('file:choose:resizedImage', function (evt) {
                        var url = evt.data.resizedUrl;
                        $('#' + inputId).val(url);
                        $('#' + previewId).attr('src', url);
                    });
                }
            });
        }
    </script>
    @yield('script')
</body>

</html>
