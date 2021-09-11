<div class="lessonbox">
    <div class="lessonbox__inner">
        <a href="{{ $combo_course->url }}" class="lessonbox__img">
            <img src="{{ $combo_course->thumbnail }}">
            @if($combo_course->original_price)
                <span class="sale">-{{ ceil(100 - $combo_course->price / $combo_course->original_price * 100) }}%</span>
            @endif
        </a>
        <div class="lessonbox__body">
            @if($combo_course->category)
                <div class="lessonbox__cat">
                    <a href="{{ $combo_course->category->url }}">{{ $combo_course->category->title }}</a>
                </div>
            @endif
            <div class="lessonbox__title">
                <a href="{{ $combo_course->url }}">{{ $combo_course->title }}</a>
            </div>
            <ul class="lessonbox__info">
                <li>
                    Khoá học:
                    @if($combo_course->items()->count() > 0)
                        {{ $combo_course->items()->count() }} khoá
                    @else
                        Đang cập nhật
                    @endif
                </li>
            </ul>

            <div class="lessonbox__footer">
                <div class="lessonbox__price">
                    <ins>{{ currency($combo_course->price) }}</ins>
                    @if($combo_course->original_price)
                        <del>{{ currency($combo_course->original_price) }}</del>
                    @endif
                </div>
                <a href="{{ $combo_course->url }}" class="btn btn--sm btn--outline">Chi tiết</a>
            </div>
        </div>
    </div>
</div>
