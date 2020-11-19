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

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS LIBRARY -->
    <link href="{{ asset('tomato/assets/font/fontawesome/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('tomato/assets/font/Pe-icon/pe-icon.css') }}" rel="stylesheet">
    <!-- <link rel="stylesheet" type="text/css" href="assets/lib/wow/animate.css"> -->
    <link rel="stylesheet" type="text/css" href="{{ asset('tomato/assets/lib/owl-carousel/owl.carousel.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('tomato/assets/lib/magnific-popup/magnific-popup.min.css') }}">

    <!-- PAGE STYLE -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,400;0,600;0,700;1,300;1,600;1,700&family=Open+Sans:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&display=swap">
    <link rel="stylesheet" type="text/css" href="{{ mix('tomato/assets/css/main.css') }}">

    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <![endif]-->

    <meta property="fb:app_id" content="141729702951631">

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
                            <li class="menu-has-children">
                                <a href="">Sách</a>
                                <ul class="submenu">
                                    @foreach(get_categories(null, 'book-categories') as $c1)
                                        <li class="{{ $c1->__subcategory_count > 0 ? 'menu-has-children' : null }}">
                                            <a href="{{ $c1->url }}">{{ $c1->title }}</a>
                                            @if($c1->__subcategory_count > 0)
                                                <ul class="submenu">
                                                    @foreach(get_categories($c1->id, 'book-categories') as $c2)
                                                        <li class="">
                                                            <a href="{{ $c2->url }}">{{ $c2->title }}</a>
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
                        @if(auth()->check())
                            <div class="header__cart">
                                <span><i class="pe-icon-cart"></i><small id="cartbox__count">0</small></span>
                            </div>
                        @endif
                        <a href="#" class="header__iconSearch"><i class="pe-icon-search"></i></a>
                        <div class="header__login">
                            @if(auth()->check())
                                <div class="dropdown">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownUserHeader" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="f-avatar" style="background-image: url('{{ auth()->user()->avatar ?? asset("tomato/assets/img/image/default-avatar.jpg") }}');"></span>
                                        <p class="f-name">{{ auth()->user()->username }}</p>
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="dropdownUserHeader">
                                        <a class="dropdown-item" href="{{ route('user.info') }}"><i class="fa fa-vcard-o"></i>Trang cá nhân</a>
                                        <a class="dropdown-item" href=""><i class="fa fa-bell-o"></i>Thông báo <small>(3)</small></a>
                                        <a class="dropdown-item" href="{{ route('user.invoice') }}"><i class="fa fa-cart-arrow-down"></i>Lịch sử mua hàng</a>
                                        <a class="dropdown-item" href="{{ route('user.my_course') }}"><i class="fa fa-server"></i>Khoá học của tôi</a>
                                        <a class="dropdown-item" href="{{ route('user.recharge') }}"><i class="fa fa-credit-card"></i>Nạp tiền</a>
                                        <a class="dropdown-item" href="{{ route('user.changepass') }}"><i class="fa fa-edit"></i>Thay đổi mật khẩu</a>
                                        <a class="dropdown-item" href="#" onclick="$('#js-logout-form').submit();"><i class="fa fa-sign-out"></i>Đăng xuất</a>
                                    </div>
                                </div>
                                <form action="{{ route('logout') }}" method="POST" id="js-logout-form">
                                    @csrf
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="header__iconLogin"><i class="pe-icon-user"></i></a>
                            @endif
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
                    @yield('headings')
                </marquee>
            </div>
        </footer>
        <!-- End/Footer -->

        <a href="#" id="backtotop">
            <span><i class="fa fa-angle-up"></i></span>
        </a>
    </div>

    <!-- Cart -->
    <div class="cartbox" id="cartbox__mini">
        <div class="cartbox__clickout"></div>
        <div class="cartbox__inner">
            <span class="cartbox__close"><i class="fa fa-close"></i></span>
            <div class="cartbox__flex d-flex align-items-start flex-column">
                <h3 class="cartbox__title">Giỏ hàng (@{{ data.length }} sản phẩm)</h3>

                <div class="cartbox__list">
                    <div v-if="loading" class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <ul v-else>
                        <li v-for="(item, index) in data" :key="item.id" class="item">
                            <span class="close" @click="removeCartItem(item.id)"><i class="fa fa-close"></i></span>
                            <div>
                                <img :src="item.object.thumbnail">
                                <h4><a :href="item.__object_url">@{{ item.object.title }}</a></h4>
                                <p>
                                    @{{ item.amount }} x <b>@{{ currency(item.price) }}</b>
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="cartbox__footer mt-auto">
                    <div class="cartbox__total">
                        Tổng <b>@{{ currency(totalPrice) }}</b>
                    </div>
                    <div class="cartbox__btn">
                        <a href="{{ route('cart') }}" class="btn btn--outline-secondary btn--block">Giỏ hàng</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End/Cart -->

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
    <script type="text/javascript" src="{{ mix('js/frontend.js') }}"></script>
    <script type="text/javascript" src="{{ asset('tomato/assets/lib/bodyScrollLock/bodyScrollLock.js') }}"></script>
    <script type="text/javascript" src="{{ asset('tomato/assets/lib/jquery-validate/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('tomato/assets/lib/headroom/headroom.js') }}"></script>
    <script type="text/javascript" src="{{ asset('tomato/assets/lib/owl-carousel/owl.carousel.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('tomato/assets/lib/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('tomato/assets/lib/theia-sticky-sidebar/theia-sticky-sidebar.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('tomato/assets/lib/wow/wow.min.js') }}"></script>
    <script>
        const vueCartbox = new Vue({
            el: '#cartbox__mini',
            data: {
                data: [],
                loading: false,
                totalPrice: 0,
            },
            mounted() {
                this.getData();
            },
            methods: {
                getData() {
                    this.loading = true;
                    axios.get("{{ route('cart.get_data') }}").then(res => {
                        this.data = res;

                        this.totalPrice = this.data.reduce((total, item) => {
                            return total + item.price * item.amount;
                        }, 0);

                        $('#cartbox__count').text(this.data.map(x => x.amount).reduce((t, x) => t + x, 0));
                    }).then(() => {
                        this.loading = false;
                    });
                },
                removeCartItem(id) {
                    this.loading = true;
                    axios.post("{{ route('cart.delete') }}", {
                        id
                    }).then(() => {
                        // nothing
                    }).then(() => {
                        this.getData();
                    });
                },
                currency(x) {
                    return currency(x);
                },
            },
        });

    </script>
    <script type="text/javascript" src="{{ asset('tomato/assets/js/main.js') }}?v={{ date('Ymd') }}"></script>

    @yield('footer')

    <div id="fb-root"></div>
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '141729702951631',
          xfbml      : true,
          version    : 'v3.2'
        });
        FB.AppEvents.logPageView();
      };

      (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement(s); js.id = id;
         js.src = "https://connect.facebook.net/vi_VN/sdk.js";
         fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));
    </script>
</body>

</html>
