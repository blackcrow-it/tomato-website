<!DOCTYPE html>
<!--[if IE 9 ]> <html class="ie ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="vi">
<!--<![endif]-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <link rel="shortcut icon" href="{{ asset('tomato/favicon.ico') }}" type="image/vnd.microsoft.icon">

    <!-- CSS LIBRARY -->
    <link href="{{ asset('tomato/assets/font/fontawesome/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('tomato/assets/font/Pe-icon/pe-icon.css') }}" rel="stylesheet">
    <!-- <link rel="stylesheet" type="text/css" href="assets/lib/wow/animate.css"> -->
    <link rel="stylesheet" type="text/css" href="{{ asset('tomato/assets/lib/owl-carousel/owl.carousel.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('tomato/assets/lib/magnific-popup/magnific-popup.min.css') }}">

    <!-- PAGE STYLE -->
    <link rel="stylesheet" type="text/css" href="{{ mix('tomato/assets/css/main.css') }}">

    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <![endif]-->

    @yield('header')
</head>

<body>
    <div class="page-wrapper">
        <!-- Header -->
        <header class="header">
            <div class="header__fixheight"></div>

            <div class="header__fixed">
                <div class="header__top">
                    <ul>
                        <li><a href="{{ route('home') }}" target="_blank">Hệ thống đào tạo Ngoại ngữ - Tin học Online Tomato</a></li>
                        <li><a href="https://ngoaingutomato.edu.vn/lich-khai-giang-cc1416.html" target="_blank">Lớp Offline</a></li>
                    </ul>
                    <div class="fix"></div>
                    <ul>
                        <li><b>Điện thoại:</b><a href="#"> 0225 657 2222</a> - <a href="#">0225 628 0123</a></li>
                        <li><b>Support: (Zalo):</b> <a href="#">0965 113 913 Mr Nam</a></li>
                    </ul>
                </div>

                <div class="header__content">
                    <div class="header__logo">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('tomato/assets/img/logo.png') }}">
                        </a>
                    </div>

                    <nav class="header__nav">
                        <ul class="menu-list">
                            <li><a href="{{ route('home') }}">Trang chủ</a></li>
                            <li class="menu-has-children">
                                <a href="">Khóa học</a>
                                <ul class="submenu">
                                    @foreach(get_categories(null, 'course-categories') as $c1)
                                        <li class="{{ $c1->__subcategory_count > 0 ? 'menu-has-children' : null }}">
                                            <a href="{{ $c1->url }}">{{ $c1->title }}</a>
                                            @if($c1->__subcategory_count > 0)
                                                <ul class="submenu">
                                                    @foreach(get_categories($c1->id, 'course-categories') as $c2)
                                                        <li class="{{ $c2->__subcategory_count > 0 ? 'menu-has-children' : null }}">
                                                            <a href="{{ $c2->url }}">{{ $c2->title }}</a>
                                                            @if($c2->__subcategory_count > 0)
                                                                <ul class="submenu">
                                                                    @foreach(get_categories($c2->id, 'course-categories') as $c3)
                                                                        <li class="">
                                                                            <a href="{{ $c3->url }}">{{ $c3->title }}</a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            @foreach(get_categories(null, 'navigator') as $c1)
                                <li class="{{ $c1->__subcategory_count > 0 ? 'menu-has-children' : null }}">
                                    <a href="{{ $c1->url }}">{{ $c1->title }}</a>
                                    @if($c1->__subcategory_count > 0)
                                        <ul class="submenu">
                                            @foreach(get_categories($c1->id, 'navigator') as $c2)
                                                <li class="{{ $c2->__subcategory_count > 0 ? 'menu-has-children' : null }}">
                                                    <a href="{{ $c2->url }}">{{ $c2->title }}</a>
                                                    @if($c2->__subcategory_count > 0)
                                                        <ul class="submenu">
                                                            @foreach(get_categories($c2->id, 'navigator') as $c3)
                                                                <li class="">
                                                                    <a href="{{ $c3->url }}">{{ $c3->title }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                            <li><a href="lienhe.html">Liên hệ</a></li>
                        </ul>
                    </nav>

                    <div class="header__tool">
                        <a href="#" class="header__iconSearch"><i class="pe-icon-search"></i></a>
                        <div class="header__login">
                            <a href="login.html" class="header__iconLogin"><i class="pe-icon-user"></i></a>
                        </div>
                        <div class="header__iconmenu">
                            <div class="f-wrap">
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="header-search">
            <span class="header-search__close"><i class="pe-icon-close"></i></span>
            <div class="header-search__inner">
                <div class="container">
                    <div class="row">
                        <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2">
                            <form class="form-search">
                                <input type="text" name="key" class="form-control">
                                <button type="submit" class="btn-submit">Tìm kiếm</button>
                            </form>

                            <h3>Từ khoá được tìm nhiều nhất</h3>
                            <ul>
                                <li><a href="#">Giáo tình tiếng Trung</a></li>
                                <li><a href="#">Học tiếng Trung hiệu quả</a></li>
                                <li><a href="#">Giáo trình tiếng Hàn</a></li>
                                <li><a href="#">Giáo tình tiếng trung</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="menu-mobile">
            <div class="menu-mobile__bg"></div>
            <div class="menu-mobile__inner">
                <nav class="menu-mobile__nav">
                    <ul class="menu-list">
                        <li><a href="{{ route('home') }}">Trang chủ</a></li>
                        @foreach(get_categories(null, 'navigator') as $c1)
                            <li class="{{ $c1->__subcategory_count > 0 ? 'menu-has-children' : null }}">
                                <a href="{{ $c1->url }}">{{ $c1->title }}</a>
                                @if($c1->__subcategory_count > 0)
                                    <ul class="submenu">
                                        @foreach(get_categories($c1->id, 'navigator') as $c2)
                                            <li class="{{ $c2->__subcategory_count > 0 ? 'menu-has-children' : null }}">
                                                <a href="{{ $c2->url }}">{{ $c2->title }}</a>
                                                @if($c2->__subcategory_count > 0)
                                                    <ul class="submenu">
                                                        @foreach(get_categories($c2->id, 'navigator') as $c3)
                                                            <li class="">
                                                                <a href="{{ $c3->url }}">{{ $c3->title }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                        <li><a href="lienhe.html">Liên hệ</a></li>
                    </ul>
                </nav>
                <div class="menu-mobile__footer">
                    <ul>
                        <li><a href="https://ngoaingutomato.edu.vn/" target="_blank">Hệ thống đào tạo Ngoại ngữ - Tin
                                học Online Tomato</a></li>
                        <li><a href="https://ngoaingutomato.edu.vn/lich-khai-giang-cc1416.html" target="_blank">Lớp
                                Offline</a></li>
                        <li><b>Điện thoại:</b><a href="#"> 0225 657 2222</a> - <a href="#">0225 628 0123</a></li>
                        <li><b>Support: (Zalo):</b> <a href="#">0965 113 913 Mr Nam</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End/Header -->

        <!-- Content -->
        <div class="page-content">
            @yield('body')
        </div>
        <!-- End/Content -->

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="footer__content">
                    <div class="row">
                        <div class="col-xl-6">
                            <h2 class="footer__logo">Công Ty Cổ Tư Vấn Và Đào Tạo TOMATO</h2>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6">
                            <div class="footer__widget widget-contact">
                                <ul>
                                    <li><b>Địa chỉ ĐKKD CS1</b> Số 300 Lạch Tray, Quận. Lê Chân, Tp. Hải Phòng</li>
                                    <li><b>Cơ sở 3:</b>Số 65 Quán Nam, Quận, Lê Chân, Tp. Hải Phòng</li>
                                    <li><b>Cơ sở 4:</b>408 Trường Sơn, An Lão, Tp. Hải Phòng</li>
                                    <li><b>Điện thoại:</b>0225 657 2222 - 0225 628 0123 <br>Support: (Zalo) 0965 113 913
                                        Mr Nam</li>
                                    <li><b>Hotline:</b>0964 299 222</li>
                                    <li><b>Email:</b>ngoaingutomatohp@gmail.com</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="row">
                                <div class="col-6 col-md-4 col-lg-3 col-xl-6">
                                    <div class="footer__widget widget-menu">
                                        <h3 class="footer__widget-title">Menu</h3>
                                        <ul>
                                            <li><a href="gioithieu.html">Giới thiệu</a></li>
                                            <li><a href="khoahoc.html">Khoá học</a></li>
                                            <li><a href="tintuc.html">Tin tức</a></li>
                                            <li><a href="top-diemcao.html">Thi thử</a></li>
                                            <li><a href="lienhe.html">Liên hệ</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4 col-lg-3 col-xl-6">
                                    <div class="footer__widget widget-menu">
                                        <h3 class="footer__widget-title">Lớp offline</h3>
                                        <ul>
                                            <li><a href="https://ngoaingutomato.edu.vn/lich-khai-giang-cc1416.html" target="_blank">Lịch khai giảng</a></li>
                                            <li><a href="https://ngoaingutomato.edu.vn/tuyen-dung-cc1417.html" target="_blank">Tuyển dụng</a></li>
                                        </ul>
                                    </div>
                                    <div class="footer__widget widget-menu">
                                        <h3 class="footer__widget-title">Website liên kết</h3>
                                        <ul>
                                            <li><a href="#">http://tomatoonline.edu.vn</a></li>
                                            <li><a href="#">ngoaingutomato.edu.vn</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4 col-lg-3 col-xl-6">
                                    <div class="footer__widget">
                                        <h3 class="footer__widget-title">Chứng nhận</h3>
                                        <a class="footer__certification">
                                            <img alt="Thông báo bộ công thương Tomato" class="Thông Tomato báo bộ công thương" longdesc="Thông báo bộ công thương Tomato" src="http://tomatoonline.edu.vn/upload/images/dathongbao.png" style="height:65px; width:170px" title="Thông báo bộ công thương Tomato">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4 col-lg-3 col-xl-6">
                                    <div class="footer__widget">
                                        <h3 class="footer__widget-title">Mạng xã hội</h3>
                                        <div class="footer__social">
                                            <a href="#"><i class="fa fa-facebook"></i></a>
                                            <a href="#"><i class="fa fa-youtube-play"></i></a>
                                            <a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="12.327" height="14.031" viewBox="0 0 12.327 14.031">
                                                    <path d="M43.743,3.9a3.535,3.535,0,0,1-2.136-.714A3.536,3.536,0,0,1,40.2.39H37.9V6.65l0,3.429a2.077,2.077,0,1,1-1.424-1.968V5.785a4.508,4.508,0,0,0-.661-.049,4.393,4.393,0,0,0-3.3,1.476,4.305,4.305,0,0,0,.194,5.936,4.469,4.469,0,0,0,.414.361,4.393,4.393,0,0,0,2.693.91,4.508,4.508,0,0,0,.661-.049,4.378,4.378,0,0,0,2.446-1.223A4.289,4.289,0,0,0,40.21,10.1L40.2,4.976A5.805,5.805,0,0,0,43.75,6.183V3.9h-.007Z" transform="translate(-31.423 -0.39)" fill="#fff" /></svg></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer__footer">
                    <p>Copyright © 2020 công ty cổ phần tư vấn và đào tạo TOMATO. Thiết kế bởi TOMATO</p>
                </div>
            </div>
            <div class="footer__marqee">
                <marquee scrolldelay="150">
                    <h1><a href="http://tomatoonline.edu.vn">Website học ngoại ngữ online</a></h1>

                    <h2><a href="http://tomatoonline.edu.vn">Website học Online</a></h2>

                    <h2><a href="http://tomatoonline.edu.vn">Học ngoại ngữ Online</a></h2>

                    <h3><a href="http://tomatoonline.edu.vn/hoc-tieng-trung-online-video-ct5.html">Học tiếng Trung
                            online</a></h3>

                    <h3><a href="http://tomatoonline.edu.vn/hoc-tieng-han-online-video-ct8.html">Học Tiếng Hàn
                            Online</a></h3>

                    <h3><a href="http://tomatoonline.edu.vn/hoc-tieng-nhat-online-video-ct9.html">Học Tiếng
                            Nhật&nbsp;Online</a></h3>

                    <h3>Học Tiếng Đức Online</h3>

                    <h3>Học Tiếng Anh&nbsp;Online</h3>

                    <h4><a href="http://tomatoonline.edu.vn">Trung tâm ngoại ngữ Tomato</a></h4>
                </marquee>
            </div>
        </footer>
        <!-- End/Footer -->

        <a href="#" id="backtotop">
            <span><i class="fa fa-angle-up"></i></span>
        </a>
    </div>

    <!-- Modal thông báo đăng ký nhận tin thành công -->
    <div class="modal fade" id="consultationForm-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <p>Cám ơn bạn <b class="show-name"></b> đã tin tưởng đăng ký khoá học <b class="show-course"></b> của
                    TOMATO Online. Chúng tôi sẽ liên hệ tư vấn sớm nhất. Chúc bạn một ngày tốt lành!</p>
                <div>
                    <button type="button" class="btn btn--outline" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End/Modal thông báo đăng ký nhận tin thành công -->

    <!-- Link Js -->
    <script type="text/javascript" src="{{ asset('tomato/assets/lib/jquery/jquery-3.3.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('tomato/assets/lib/bodyScrollLock/bodyScrollLock.js') }}"></script>
    <script type="text/javascript" src="{{ asset('tomato/assets/lib/jquery-validate/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('tomato/assets/lib/boostrap/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('tomato/assets/lib/boostrap/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('tomato/assets/lib/headroom/headroom.js') }}"></script>
    <script type="text/javascript" src="{{ asset('tomato/assets/lib/owl-carousel/owl.carousel.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('tomato/assets/lib/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('tomato/assets/lib/theia-sticky-sidebar/theia-sticky-sidebar.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('tomato/assets/lib/wow/wow.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('tomato/assets/js/main.js') }}"></script>

    @yield('footer')
</body>

</html>
