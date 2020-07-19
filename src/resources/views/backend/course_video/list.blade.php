@extends('backend.master')

@section('title')
Video
@endsection

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-8">
        <h1 class="m-0 text-dark">Video trong khoá học {{ $course->title }}</h1>
    </div><!-- /.col -->
    <div class="col-sm-4">
        <div class="float-sm-right">
            <a href="{{ route('admin.course_video.add', [ 'courseId' => $course->id ]) }}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Thêm mới</a>
        </div>
    </div><!-- /.col -->
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
                <th>Ảnh thu nhỏ</th>
                <th>Tiêu đề</th>
                <th>Transcode</th>
                <th>Hiển thị</th>
                <th data-toggle="tooltip" title="Thứ tự trong khoá học">Thứ tự</th>
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
                    </td>
                    <td>
                        {{ $item->job_progress }}%
                    </td>
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input js-switch-enabled" {{ $item->enabled ? 'checked' : '' }} id="cs-enabled-{{ $item->id }}" data-id="{{ $item->id }}">
                            <label class="custom-control-label" for="cs-enabled-{{ $item->id }}"></label>
                        </div>
                    </td>
                    <td>
                        <input type="text" value="{{ $item->order_in_course }}" data-id="{{ $item->id }}" class="custom-order">
                    </td>
                    <td class="text-nowrap">
                        <form action="{{ route('admin.course_video.delete', [ 'id' => $item->id ]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa video này?')">
                            @csrf
                            <a href="{{ route('admin.course_video.edit', [ 'id' => $item->id ]) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Sửa</a>
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('script')
<script>
    $('.js-switch-enabled').change(function() {
        var that = this;
        $(that).prop('disabled', true);

        $.post('{{ route("admin.course_video.enabled") }}', {
            id: $(that).data('id'),
            enabled: $(that).prop('checked')
        }).fail(function() {
            alert('Không thể đổi trạng thái kích hoạt. Vui lòng thử lại.')
        }).always(function() {
            $(that).prop('disabled', false);
        });
    });

    $('.custom-order').change(function() {
        var that = this;
        $(that).prop('disabled', true);

        $.post('{{ route("admin.course_video.order_in_course") }}', {
            id: $(that).data('id'),
            order_in_course: $(that).val()
        }).fail(function() {
            alert('Có lỗi xảy ra, vui lòng thử lại.');
        }).always(function() {
            $(that).prop('disabled', false);
        });
    });
</script>
@endsection
