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
                <li>Bài học: {{ $course->__lesson_count }} bài</li>
                <li>Giảng viên: <span class="text-danger">{{ $course->lecturer_name }}</span></li>
                @switch($course->level)
                    @case(\App\Constants\CourseLevel::ELEMENTARY)
                        <li>Trình độ: Sơ cấp</li>
                        @break
                    @case(\App\Constants\CourseLevel::INTERMEDIATE)
                        <li>Trình độ: Trung cấp</li>
                        @break
                    @case(\App\Constants\CourseLevel::ADVANCED)
                        <li>Trình độ: Cao cấp</li>
                        @break
                    @default
                        <li>Trình độ: Không phân loại</li>
                        @break
                @endswitch
            </ul>

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
