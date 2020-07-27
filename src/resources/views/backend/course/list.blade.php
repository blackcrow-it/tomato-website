@extends('backend.master')

@section('title')
Khóa học
@endsection

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Khóa học</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.course.add') }}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Thêm mới</a>
        </div>
    </div><!-- /.col -->
</div>
<div class="row mb-2">
    <div class="col-sm-4">
        <select class="form-control" id="filter-post-in-category-id">
            <option value="">Danh mục gốc</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ request()->input('category_id') == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
            @endforeach
        </select>
    </div>
</div>
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
                <th>Ảnh đại diện</th>
                <th>Tiêu đề</th>
                <th>Giá tiền</th>
                <th>Hiển thị</th>
                @if (request()->input('category_id'))
                    <th data-toggle="tooltip" title="Thứ tự trong danh mục">Thứ tự</th>
                @endif
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>
                        <img src="{{ $item->thumbnail }}" class="img-thumbnail">
                    </td>
                    <td>
                        {{ $item->title }}
                        <br>
                        <a href="{{ route('course', [ 'slug' => $item->slug ]) }}" target="_blank"><small><em>{{ route('course', [ 'slug' => $item->slug ]) }}</em></small></a>
                        <br>
                        <small>
                            <i class="fas fa-eye" data-toggle="tooltip" title="Lượt xem"></i> {{ $item->view }}
                            <span class="mr-3"></span>
                            <i class="fas fa-user-plus" data-toggle="tooltip" title="Người đăng bài"></i> {{ $item->owner->username ?? '' }}
                            <span class="mr-3"></span>
                            <i class="fas fa-calendar-alt" data-toggle="tooltip" title="Thời gian đăng bài"></i> {{ $item->created_at }}
                            <span class="mr-3"></span>
                            <i class="fas fa-user-edit" data-toggle="tooltip" title="Người sửa cuối"></i> {{ $item->last_editor->username ?? '' }}
                            <span class="mr-3"></span>
                            <i class="fas fa-calendar-alt" data-toggle="tooltip" title="Thời gian sửa"></i> {{ $item->updated_at }}
                        </small>
                    </td>
                    <td>{{ currency($item->price) }}</td>
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input js-switch-enabled" {{ $item->enabled ? 'checked' : '' }} id="cs-enabled-{{ $item->id }}" data-id="{{ $item->id }}">
                            <label class="custom-control-label" for="cs-enabled-{{ $item->id }}"></label>
                        </div>
                    </td>
                    @if (request()->input('category_id'))
                        <td>
                            <input type="text" value="{{ $item->order_in_category }}" data-id="{{ $item->id }}" class="custom-order">
                        </td>
                    @endif
                    <td class="text-nowrap">
                        <form action="{{ route('admin.course.delete', [ 'id' => $item->id ]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khóa học này?')">
                            @csrf
                            <a href="{{ route('admin.course_video.list', [ 'course_id' => $item->id ]) }}" class="btn btn-sm btn-info"><i class="fas fa-video"></i> Video</a>
                            <a href="{{ route('admin.course.edit', [ 'id' => $item->id ]) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Sửa</a>
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
    $('.js-switch-enabled').change(function() {
        var that = this;
        $(that).prop('disabled', true);

        $.post('{{ route("admin.course.enabled") }}', {
            id: $(that).data('id'),
            enabled: $(that).prop('checked')
        }).fail(function() {
            alert('Không thể đổi trạng thái kích hoạt. Vui lòng thử lại.')
        }).always(function() {
            $(that).prop('disabled', false);
        });
    });

    $('#filter-post-in-category-id').change(function() {
        var category_id = $(this).val();
        var url = new URL(location.href);
        url.searchParams.set('category_id', category_id);
        location.href = url.toString();
    });
</script>

@if (request()->input('category_id'))
    <script>
        $('.custom-order').change(function() {
            var that = this;
            $(that).prop('disabled', true);

            $.post('{{ route("admin.course.order_in_category") }}', {
                id: $(that).data('id'),
                order_in_category: $(that).val()
            }).fail(function() {
                alert('Có lỗi xảy ra, vui lòng thử lại.');
            }).always(function() {
                $(that).prop('disabled', false);
            });
        });
    </script>
@endif
@endsection
