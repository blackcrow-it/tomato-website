<div class="lessonbox">
    <div class="lessonbox__inner">
        <?php
            $price_origin = 0;
            foreach ($combo_course->items as $c_course) {
                $price_origin += $c_course->course->price;
            }
        ?>
        <a href="{{ $combo_course->url }}" class="lessonbox__img">
            <img src="{{ $combo_course->thumbnail }}">
            @if($price_origin)
                <span class="sale">-{{ ceil(100 - $combo_course->price / $price_origin * 100) }}%</span>
            @endif
        </a>
        <div class="lessonbox__body">
            @if($combo_course->category)
                <div class="lessonbox__cat">
                    <a href="{{ $combo_course->category->url }}">{{ $combo_course->category->title }}</a>
                </div>
            @endif
            <h3 class="lessonbox__title">
                <a href="{{ $combo_course->url }}">{{ $combo_course->title }}</a>
            </h3>
            <ul class="lessonbox__info">
                <li class="meta-course">
                    Khoá học:
                    @if($combo_course->items()->count() > 0)
                        {{ $combo_course->items()->count() }} khoá
                    @else
                        Đang cập nhật
                    @endif
                </li>
                <li class="meta-rating">Đánh giá:
                    <span class="lessonbox__rating">
                        @if ($combo_course->getAvgRating() == 0)
                        <i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>
                        @elseif ($combo_course->getAvgRating() <= 0.5)
                        <i class="fa fa-star-half-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>
                        @elseif ($combo_course->getAvgRating() <= 1)
                        <i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>
                        @elseif ($combo_course->getAvgRating() <= 1.5)
                        <i class="fa fa-star"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>
                        @elseif ($combo_course->getAvgRating() <= 2)
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>
                        @elseif ($combo_course->getAvgRating() <= 2.5)
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>
                        @elseif ($combo_course->getAvgRating() <= 3)
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>
                        @elseif ($combo_course->getAvgRating() <= 3.5)
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star-o"></i>
                        @elseif ($combo_course->getAvgRating() <= 4)
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i>
                        @elseif ($combo_course->getAvgRating() <= 4.5)
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i>
                        @elseif ($combo_course->getAvgRating() <= 5)
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                        @endif
                    </span>
                    ({{$combo_course->getAvgRating()}})
                </li>
            </ul>
            <br/>
            <div class="lessonbox__total_sell"><img class="img__icon" src="{{ asset('images/icon-sell.svg') }}" alt=""> Đã bán: <b>{{ count($combo_course->totalSell()) }}</b></div>
            <div class="lessonbox__footer">
                <div class="lessonbox__price">
                    <ins>{{ currency($combo_course->price) }}</ins>
                    @if($price_origin)
                        <del>{{ currency($price_origin) }}</del>
                    @endif
                </div>
                <a href="{{ $combo_course->url }}" class="btn btn--sm btn--outline">Chi tiết</a>
            </div>
        </div>
    </div>
</div>
