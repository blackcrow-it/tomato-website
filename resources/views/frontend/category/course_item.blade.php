<div class="lessonbox">
    <div class="lessonbox__inner">
        <a href="{{ $course->url }}" class="lessonbox__img">
            <img src="{{ $course->thumbnail }}">
            @if($course->original_price)
                <span class="sale">-{{ ceil(100 - $course->price / $course->original_price * 100) }}%</span>
            @endif
        </a>
        <div class="lessonbox__body">
            @if($course->category)
                <div class="lessonbox__cat">
                    <a href="{{ $course->category->url }}">{{ $course->category->title }}</a>
                </div>
            @endif
            <h3 class="lessonbox__title">
                <a href="{{ $course->url }}">{{ $course->title }}</a>
            </h3>
            <ul class="lessonbox__info">
                <li class="meta-course">
                    Bài học:
                    @if($course->lessons->count() > 0)
                        {{ $course->lessons->count() }} bài
                    @else
                        Đang cập nhật
                    @endif
                </li>
                <li class="meta-teacher">Giảng viên: <span class="text-danger">{{ $course->teacher->name ?? 'Tomato Online' }}</span></li>
                @switch($course->level)
                    @case(\App\Constants\CourseLevel::ELEMENTARY)
                        <li class="meta-lever">Trình độ: Sơ cấp</li>
                        @break
                    @case(\App\Constants\CourseLevel::INTERMEDIATE)
                        <li class="meta-lever">Trình độ: Trung cấp</li>
                        @break
                    @case(\App\Constants\CourseLevel::ADVANCED)
                        <li class="meta-lever">Trình độ: Cao cấp</li>
                        @break
                    @default
                        <li class="meta-lever">Trình độ: Không phân loại</li>
                        @break
                @endswitch
                <li class="meta-rating">Đánh giá:
                    <span class="lessonbox__rating">
                        @if ($course->getAvgRating() == 0)
                        <i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>
                        @elseif ($course->getAvgRating() <= 0.5)
                        <i class="fa fa-star-half-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>
                        @elseif ($course->getAvgRating() <= 1)
                        <i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>
                        @elseif ($course->getAvgRating() <= 1.5)
                        <i class="fa fa-star"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>
                        @elseif ($course->getAvgRating() <= 2)
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>
                        @elseif ($course->getAvgRating() <= 2.5)
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>
                        @elseif ($course->getAvgRating() <= 3)
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>
                        @elseif ($course->getAvgRating() <= 3.5)
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star-o"></i>
                        @elseif ($course->getAvgRating() <= 4)
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i>
                        @elseif ($course->getAvgRating() <= 4.5)
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i>
                        @elseif ($course->getAvgRating() <= 5)
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                        @endif
                    </span>
                    ({{$course->getAvgRating()}})
                </li>
            </ul>
            <br/>
            <div class="lessonbox__total_sell"><img class="img__icon" src="{{ asset('images/icon-sell.svg') }}" alt=""> Đã bán: <b>{{ count($course->totalSell()) }}</b></div>
            <div class="lessonbox__footer">
                <div class="lessonbox__price">
                    <ins>{{ currency($course->price) }}</ins>
                    @if($course->original_price)
                        <del>{{ currency($course->original_price) }}</del>
                    @endif
                </div>
                <a href="{{ $course->url }}" class="btn btn--sm btn--outline">Chi tiết</a>
            </div>
        </div>
    </div>
</div>
