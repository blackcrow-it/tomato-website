@extends('frontend.master')

@section('header')
<title>{{ $category->meta_title ?? $category->title }}</title>
<meta content="description" value="{{ $category->meta_description ?? $category->description }}">
<meta property="og:title" content="{{ $category->og_title ?? $category->meta_title ?? $category->title }}">
<meta property="og:description" content="{{ $category->og_description ?? $category->meta_description ?? $category->description }}">
<meta property="og:url" content="{{ $category->url }}">
<meta property="og:image" content="{{ $category->og_image ?? $category->cover }}">
@endsection

@section('body')
<section class="section page-title">
    <div class="container">
        <nav class="breadcrumb-nav">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home.html">Trang chủ</a></li>
            </ol>
        </nav>
        <h1 class="page-title__title">Khoá học tiếng Trung</h1>
    </div>
</section>

<section class="section wow">
    <div class="container">
        <div class="layout layout--left">
            <div class="row">
                <div class="col-xl-3">
                    <div class="layout-sidebar">
                        <div class="widget widget--lessonCat">
                            <h2 class="widget__title">Khoá học</h2>
                            <div class="f-scroll">
                                <ul>
                                    <li class="menu-has-children current">
                                        <a href="#">Tiếng trung</a>
                                        <ul class="submenu">
                                            <li class="menu-has-children">
                                                <a href="giaotrinhhanngu.html">Giáo trình Hán ngữ (mới)</a>
                                                <ul class="submenu">
                                                    <li><a href="giaotrinhhanngu.html">Giáo trình Boya</a></li>
                                                    <li><a href="giaotrinhhanngu.html">Tiếng Trung văn phòng</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="giaotrinhhanngu.html">Giáo trình Boya</a></li>
                                            <li><a href="giaotrinhhanngu.html">Tiếng Trung văn phòng</a></li>
                                        </ul>

                                    </li>
                                    <li><a href="#">Tiếng Hàn</a></li>
                                    <li><a href="#">Nhật</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="widget widget--filterbox d-none d-xl-block">
                            <h2 class="widget__title">Bộ lọc</h2>

                            <form class="form-filter">
                                <ul>
                                    <li>
                                        <h3>Trình độ</h3>
                                        <label>
                                            <input type="radio" name="checkbox1">
                                            <span></span>
                                            <p>Sơ cấp</p>
                                        </label>
                                        <label>
                                            <input type="radio" name="checkbox1">
                                            <span></span>
                                            <p>Trung cấp</p>
                                        </label>
                                        <label>
                                            <input type="radio" name="checkbox1">
                                            <span></span>
                                            <p>Cao cấp</p>
                                        </label>
                                    </li>
                                    <li>
                                        <h3>Chính sách</h3>
                                        <label>
                                            <input type="radio" name="checkbox2">
                                            <span></span>
                                            <p>Mặc định</p>
                                        </label>
                                        <label>
                                            <input type="radio" name="checkbox2">
                                            <span></span>
                                            <p>Khuyến mãi</p>
                                        </label>
                                        <label>
                                            <input type="radio" name="checkbox2">
                                            <span></span>
                                            <p>Miễn phí</p>
                                        </label>
                                    </li>
                                    <li>
                                        <h3>Thời lượng</h3>
                                        <label>
                                            <input type="radio" name="checkbox3">
                                            <span></span>
                                            <p>Dưới 5 bài</p>
                                        </label>
                                        <label>
                                            <input type="radio" name="checkbox4">
                                            <span></span>
                                            <p>Dưới 10 bài</p>
                                        </label>
                                        <label>
                                            <input type="radio" name="checkbox5">
                                            <span></span>
                                            <p>Dưới 20 bài</p>
                                        </label>
                                    </li>
                                    <li>
                                        <button type="submit" class="btn btn--sm btn--block">Lọc</button>
                                    </li>
                                </ul>
                            </form>
                        </div>

                        <div class="widget widget--docLive">
                            <h2 class="widget__title">Tài liệu livestream</h2>
                            <ul>
                                <li><a href="#">23/10 + clip</a></li>
                                <li><a href="#">23/10 + clip</a></li>
                                <li><a href="#">23/10 + clip</a></li>
                            </ul>
                        </div>

                        <div class="widget widget--mockTest d-none d-xl-block">
                            <h2 class="widget__title">Kiểm tra kiến thức</h2>
                            <div class="f-content">
                                <a href="thithudauvao.html" class="btn btn--secondary">Vào thi</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="layout-content">
                        <div class="row spacing-custom lessonbox-wrap-style">
                            @foreach($list as $course)
                                <div class="col-6 col-lg-4">
                                    @include('frontend.category.course_item', [ 'course' => $course ])
                                </div>
                            @endforeach
                        </div>

                        <nav class="pagination text-center">
                            {{ $list->withQueryString()->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section bg-gray" id="consultationForm">
    <div class="container">
        <div class="consultationForm">
            <div class="row no-gutters">
                <div class="col-md-6">
                    <div class="consultationForm__content">
                        <div class="consultationForm__fix">
                            <h2 class="consultationForm__title">Đăng ký nhận tin</h2>
                            <form class="consultationForm__form">
                                <div class="input-item">
                                    <div class="input-item__inner">
                                        <input type="text" name="name" placeholder="Họ và tên" class="form-control">
                                    </div>
                                </div>
                                <div class="input-item">
                                    <div class="input-item__inner">
                                        <input type="text" name="phone" placeholder="Số điện thoại" class="form-control">
                                    </div>
                                </div>
                                <div class="input-item">
                                    <div class="input-item__inner">
                                        <input type="text" name="email" placeholder="Email" class="form-control">
                                    </div>
                                </div>
                                <div class="input-item">
                                    <div class="input-item__inner">
                                        <select class="form-control" name="course">
                                            <option>Khoá học tiếng Hàn</option>
                                            <option>Khoá học tiếng Trung</option>
                                            <option>Khoá học tiếng Nhật</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="input-item">
                                    <div class="input-item__inner">
                                        <textarea type="text" name="name" placeholder="Nội dung" class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="button-item">
                                    <button type="submit" class="btn">Nhận tư vấn</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="consultationForm__bg" style="background-image: url(assets/img/image/consultationForm-bg.jpg);"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
