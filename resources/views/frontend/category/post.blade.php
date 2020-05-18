@extends('frontend.master')

@section('seo')
<title>Category</title>
@endsection

@section('content')
<ul>
    @foreach ($list as $item)
        <li>
            <a href="{{ route('course', [ 'slug' => $item->slug ]) }}">
                <img src="{{ $item->thumbnail }}" alt="{{ $item->title }}" style="width:60px;height:60px;object-fit:cover;">
                <span>[{{ $item->order_in_category }}] {{ $item->title }}</span>
            </a>
        </li>
    @endforeach
</ul>
@endsection
