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
            <a href="{{ route('admin.category.add', [ 'parent_id' => $currentCategory->id ?? null ]) }}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Thêm mới</a>
        </div>
    </div><!-- /.col -->
</div>

<form action="" method="GET">
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <select class="form-control" name="filter[parent_id]">
                    <option value="">--- Danh mục gốc ---</option>
                    @foreach($categories as $item)
                        <option value="{{ $item->id }}" {{ request()->input('filter.parent_id') == $item->id ? 'selected' : '' }}>{{ $item->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <select class="form-control" name="filter[position]">
                    <option value="">--- Vị trí hiển thị ---</option>
                    @foreach(get_template_position(\App\Constants\ObjectType::CATEGORY) as $item)
                        <option value="{{ $item['code'] }}" {{ request()->input('filter.position') == $item['code'] ? 'selected' : '' }}>{{ $item['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <button class="btn btn-primary" type="submit">Tìm kiếm</button>
    </div>
</form>

<nav>
    <ol class="breadcrumb">
        @if($currentCategory)
            <li class="breadcrumb-item"><a href="{{ route('admin.category.list') }}">Danh mục gốc</a></li>
            @foreach($currentCategory->ancestors as $item)
                <li class="breadcrumb-item"><a href="{{ route('admin.category.list', [ 'id' => $item->id ]) }}">{{ $item->title }}</a></li>
            @endforeach
            <li class="breadcrumb-item active">{{ $currentCategory->title }}</li>
        @else
            <li class="breadcrumb-item active">Danh mục gốc</li>
        @endif
    </ol>
</nav>
@endsection

@section('content')
@if($errors->any())
    <div class="callout callout-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $msg)
                <li>{{ $msg }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="callout callout-success">
        @if(is_array(session('success')))
            <ul class="mb-0">
                @foreach(session('success') as $msg)
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
                <th>Hiển thị</th>
                @if(request()->input('filter.position'))
                    <th data-toggle="tooltip" title="Thứ tự hiển thị">Thứ tự</th>
                @endif
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($list as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>
                        <img src="{{ $item->icon }}" class="img-thumbnail">
                    </td>
                    <td>
                        {{ $item->title }}
                        <br>
                        <?php
                            $filterQuery = request()->input('filter');
                            $filterQuery['parent_id'] = $item->id;
                        ?>
                        <a href="{{ route('admin.category.list', [ 'filter' => $filterQuery ]) }}" class="text-primary"><small>Xem {{ $item->__subcategory_count }} danh mục con</small></a>
                    </td>
                    <td>
                        @switch($item->type)
                            @case(\App\Constants\ObjectType::COURSE)
                                <i class="fas fa-graduation-cap"></i>
                                Khóa học
                                @break
                            @case(\App\Constants\ObjectType::COMBO_COURSE)
                                <i class="fas fa-boxes"></i>
                                Combo khóa học
                                @break
                            @case(\App\Constants\ObjectType::POST)
                                <i class="fas fa-newspaper"></i>
                                Bài viết
                                @break
                            @case(\App\Constants\ObjectType::BOOK)
                                <i class="fas fa-book"></i>
                                Sách
                                @break
                        @endswitch
                    </td>
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input js-switch-enabled" {{ $item->enabled ? 'checked' : '' }} id="cs-enabled-{{ $item->id }}" data-id="{{ $item->id }}">
                            <label class="custom-control-label" for="cs-enabled-{{ $item->id }}"></label>
                        </div>
                    </td>
                    @if(request()->input('filter.position'))
                        <td>
                            <input type="text" value="{{ $item->__order_in_position }}" data-id="{{ $item->id }}" class="custom-order js-order-in-position">
                        </td>
                    @endif
                    <td class="text-nowrap">
                        <form action="{{ route('admin.category.delete', [ 'id' => $item->id ]) }}" method="POST" onsubmit="return confirm('Các danh mục con cũng sẽ bị xóa. Các bài viết trong danh mục sẽ không nằm trong danh mục nào nữa. Bạn có chắc chắn muốn xóa danh mục này?')">
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

@section('script')
<script>
    $('.js-switch-enabled').change(function () {
        var that = this;
        $(that).prop('disabled', true);

        $.post('{{ route("admin.category.enabled") }}', {
            id: $(that).data('id'),
            enabled: $(that).prop('checked')
        }).fail(function () {
            alert('Không thể đổi trạng thái kích hoạt. Vui lòng thử lại.')
        }).always(function () {
            $(that).prop('disabled', false);
        });
    });

    $('.js-order-in-position').change(function () {
        var that = this;
        $(that).prop('disabled', true);

        $.post('{{ route("admin.category.order_in_position") }}', {
            id: $(that).data('id'),
            code: '{{ request()->input("filter.position") }}',
            order_in_position: $(that).val()
        }).fail(function () {
            alert('Có lỗi xảy ra, vui lòng thử lại.');
        }).always(function () {
            $(that).prop('disabled', false);
        });
    });

</script>
@endsection
