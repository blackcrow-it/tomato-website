@extends('backend.master')

@section('title')
Combo khóa học
@endsection

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Combo khóa học</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.combo_courses.add') }}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Thêm mới</a>
        </div>
    </div><!-- /.col -->
</div>
{{-- <form action="" method="GET">
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <select class="form-control" name="filter[category_id]">
                    <option value="">--- Danh mục gốc ---</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request()->input('filter.category_id') == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <select class="form-control" name="filter[position]">
                    <option value="">--- Vị trí hiển thị ---</option>
                    @foreach(get_template_position(\App\Constants\ObjectType::COURSE) as $item)
                        <option value="{{ $item['code'] }}" {{ request()->input('filter.position') == $item['code'] ? 'selected' : '' }}>{{ $item['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <button class="btn btn-primary" type="submit">Tìm kiếm</button>
    </div>
</form> --}}
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
                <th>Ảnh đại diện</th>
                <th>Tiêu đề</th>
                <th>Giá tiền</th>
                <th>Hiển thị</th>
                @if(request()->input('filter.position'))
                    <th data-toggle="tooltip" title="Thứ tự hiển thị">Thứ tự</th>
                @elseif(request()->input('filter.category_id'))
                    <th data-toggle="tooltip" title="Thứ tự trong danh mục">Thứ tự</th>
                @endif
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($list as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>
                        <img src="{{ $item->thumbnail }}" class="img-thumbnail">
                    </td>
                    <td>
                        {{ $item->title }}
                        <br>
                        <a href="{{ route('combo_courses', [ 'slug' => $item->slug, 'id' => $item->id ]) }}" target="_blank"><small><em>{{ route('combo_courses', [ 'slug' => $item->slug, 'id' => $item->id ]) }}</em></small></a>
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
                    <td>
                        {{ currency($item->price) }}<br>
                        @if($item->original_price)
                            <del>{{ currency($item->original_price) }}</del>
                        @endif
                    </td>
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input js-switch-enabled" {{ $item->enabled ? 'checked' : '' }} id="cs-enabled-{{ $item->id }}" data-id="{{ $item->id }}">
                            <label class="custom-control-label" for="cs-enabled-{{ $item->id }}"></label>
                        </div>
                    </td>
                    <td class="text-nowrap">
                        <form action="{{ route('admin.combo_courses.delete', [ 'id' => $item->id ]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa combo khóa học này?')">
                            @csrf
                            <a href="{{ route('admin.combo_courses.edit', [ 'id' => $item->id ]) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Sửa</a>
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

        $.post('{{ route("admin.combo_courses.enabled") }}', {
            id: $(that).data('id'),
            enabled: $(that).prop('checked')
        }).fail(function () {
            alert('Không thể đổi trạng thái kích hoạt. Vui lòng thử lại.')
        }).always(function () {
            $(that).prop('disabled', false);
        });
    });

</script>
@endsection
