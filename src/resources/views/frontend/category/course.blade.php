@extends('frontend.master')

@section('seo')
<title>Category</title>
@endsection

@section('content')
<table class="table table-light table-hovered table-borderless">
    <thead class="bg-info text-white">
        <tr>
            <th>ID</th>
            <th>Avatar</th>
            <th>Name</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($list as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>
                    <img src="{{ $item->thumbnail }}" alt="{{ $item->title }}" style="width:60px;height:60px;object-fit:cover;">
                </td>
                <td>{{ $item->title }}</td>
                <td>{{ currency($item->price) }}</td>
                <td>
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $item->id }}">
                        <button type="submit" class="btn btn-success">Mua ngay</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
