@extends('frontend.category.master')

@section('category_body')
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

                        {{ $list->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
