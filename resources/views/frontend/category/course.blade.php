@extends('frontend.category.master')

@section('category_body')
<section class="section wow">
    <div class="container">
        <div class="layout layout--left">
            <div class="row">
                <div class="col-xl-3">
                    <div class="layout-sidebar">
                        <div class="widget widget--lessonCat">
                            <div class="widget__title">Khoá học</div>
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
                            <div class="widget__title">Bộ lọc</div>

                            <form class="form-filter" method="GET" action="">
                                <ul>
                                    <li>
                                        <div class="h3">Trình độ</div>
                                        <label>
                                            <input type="radio" name="filter[level]" value="" {{ request()->input('filter.level') == null ? 'checked' : null }}>
                                            <span></span>
                                            <p>Tất cả</p>
                                        </label>
                                        <label>
                                            <input type="radio" name="filter[level]" value="{{ \App\Constants\CourseLevel::ELEMENTARY }}" {{ request()->input('filter.level') == \App\Constants\CourseLevel::ELEMENTARY ? 'checked' : null }}>
                                            <span></span>
                                            <p>Sơ cấp</p>
                                        </label>
                                        <label>
                                            <input type="radio" name="filter[level]" value="{{ \App\Constants\CourseLevel::INTERMEDIATE }}" {{ request()->input('filter.level') == \App\Constants\CourseLevel::INTERMEDIATE ? 'checked' : null }}>
                                            <span></span>
                                            <p>Trung cấp</p>
                                        </label>
                                        <label>
                                            <input type="radio" name="filter[level]" value="{{ \App\Constants\CourseLevel::ADVANCED }}" {{ request()->input('filter.level') == \App\Constants\CourseLevel::ADVANCED ? 'checked' : null }}>
                                            <span></span>
                                            <p>Cao cấp</p>
                                        </label>
                                    </li>
                                    <li>
                                        <div class="h3">Chính sách</div>
                                        <label>
                                            <input type="radio" name="filter[promotion]" value="" {{ request()->input('filter.promotion') == null ? 'checked' : null }}>
                                            <span></span>
                                            <p>Tất cả</p>
                                        </label>
                                        <label>
                                            <input type="radio" name="filter[promotion]" value="discount" {{ request()->input('filter.promotion') == 'discount' ? 'checked' : null }}>
                                            <span></span>
                                            <p>Khuyến mãi</p>
                                        </label>
                                        <label>
                                            <input type="radio" name="filter[promotion]" value="free" {{ request()->input('filter.promotion') == 'free' ? 'checked' : null }}>
                                            <span></span>
                                            <p>Miễn phí</p>
                                        </label>
                                    </li>
                                    <li>
                                        <div class="h3">Thời lượng</div>
                                        <label>
                                            <input type="radio" name="filter[lesson_count]" value="" {{ request()->input('filter.lesson_count') == null ? 'checked' : null }}>
                                            <span></span>
                                            <p>Tất cả</p>
                                        </label>
                                        <label>
                                            <input type="radio" name="filter[lesson_count]" value="5" {{ request()->input('filter.lesson_count') == 5 ? 'checked' : null }}>
                                            <span></span>
                                            <p>Dưới 5 bài</p>
                                        </label>
                                        <label>
                                            <input type="radio" name="filter[lesson_count]" value="10" {{ request()->input('filter.lesson_count') == 10 ? 'checked' : null }}>
                                            <span></span>
                                            <p>Dưới 10 bài</p>
                                        </label>
                                        <label>
                                            <input type="radio" name="filter[lesson_count]" value="20" {{ request()->input('filter.lesson_count') == 20 ? 'checked' : null }}>
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
                            <div class="widget__title">Tài liệu livestream</div>
                            <ul>
                                <li><a href="#">23/10 + clip</a></li>
                                <li><a href="#">23/10 + clip</a></li>
                                <li><a href="#">23/10 + clip</a></li>
                            </ul>
                        </div>

                        <div class="widget widget--mockTest d-none d-xl-block">
                            <div class="widget__title">Kiểm tra kiến thức</div>
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
