@extends('backend.master')

@section('title')
Danh mục
@endsection

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Danh mục</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.category.add') }}?parent_id={{ $category->id ?? '' }}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Thêm mới</a>
            @if ($category != null)
                <a href="{{ route('admin.category.list', [ 'id' => $category->parent_id ]) }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
            @endif
        </div>
    </div><!-- /.col -->
</div>

<nav>
    <ol class="breadcrumb">
        @if ($category)
            <li class="breadcrumb-item"><a href="{{ route('admin.category.list') }}">Danh mục gốc</a></li>
            @foreach ($category->ancestors as $item)
                <li class="breadcrumb-item"><a href="{{ route('admin.category.list', [ 'id' => $item->id ]) }}">{{ $item->title }}</a></li>
            @endforeach
            <li class="breadcrumb-item active">{{ $category->title }}</li>
        @else
            <li class="breadcrumb-item active">Danh mục gốc</li>
        @endif
    </ol>
</nav>
@endsection

@section('content')
@if ($errors->any())
    <div class="callout callout-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $msg)
                <li>{{ $msg }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="callout callout-success">
        @if (is_array(session('success')))
            <ul class="mb-0">
                @foreach (session('success') as $msg)
                    <li>{{ $msg }}</li>
                @endforeach
            </ul>
        @else
            {{ session('success') }}
        @endif
    </div>
@endif

<div class="table-responsive">
    <table class="table table-hover table-light">
        <thead class="bg-lightblue">
            <tr>
                <th>ID</th>
                <th>Icon</th>
                <th>Tiêu đề</th>
                <th>Loại</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>
                        <i class="{{ $item->icon }}"></i>
                    </td>
                    <td>
                        {{ $item->title }}
                        <br>
                        <a href="{{ route('admin.category.list', [ 'id' => $item->id ]) }}" class="text-primary"><small>Xem {{ $item->descendants->count() }} danh mục con</small></a>
                    </td>
                    <td>
                        @switch ($item->type)
                            @case (\App\Category::TYPE_COURSE)
                                <i class="fas fa-graduation-cap"></i>
                                Khóa học
                                @break
                            @case (\App\Category::TYPE_POST)
                                <i class="fas fa-newspaper"></i>
                                Bài viết
                                @break
                        @endswitch
                    </td>
                    <td class="text-nowrap">
                        <form action="{{ route('admin.category.delete', [ 'id' => $item->id ]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">
                            @csrf
                            <a href="{{ route('admin.category.edit', [ 'id' => $item->id ]) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Sửa</a>
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $list->withQueryString()->links() }}
@endsection
