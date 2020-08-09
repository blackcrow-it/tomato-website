<div class="lessonbox">
    <div class="lessonbox__inner">
        <a href="{{ $course->url }}" class="lessonbox__img">
            <img src="{{ $course->thumbnail }}">
            @if($course->original_price)
                <span class="sale">-{{ ceil(100 - $course->price / $course->original_price * 100) }}%</span>
            @endif
        </a>
        <div class="lessonbox__body">
            @if ($course->category)
                <div class="lessonbox__cat">
                    <a href="{{ $course->category->url }}">{{ $course->category->title }}</a>
                </div>
            @endif
            <h3 class="lessonbox__title">
                <a href="{{ $course->url }}">{{ $course->title }}</a>
            </h3>
            <ul class="lessonbox__info">
                <li>Bài học: 15 bài</li>
                <li>Giảng viên: <a href="#">Bùi Thu Hà</a></li>
                <li>Trình độ: Mới bắt đầu</li>
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
