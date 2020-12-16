<div class="bookBox">
    <a href="{{ $book->url }}" class="bookBox__img">
        <img src="{{ $book->thumbnail }}" alt="{{ $book->title }}">
        @if ($book->original_price)
            <span class="sale">-{{ 100 - ceil($book->price / $book->original_price * 100) }}%</span>
        @endif
    </a>
    <div class="bookBox__body">
        <div class="bookBok__title"><a href="{{ $book->url }}">{{ $book->title }}</a></div>
        <div class="bookBok__price">
            <ins>{{ currency($book->price) }}</ins>
            @if ($book->original_price)
                <del>{{ currency($book->original_price) }}</del>
            @endif
        </div>
    </div>
</div>
