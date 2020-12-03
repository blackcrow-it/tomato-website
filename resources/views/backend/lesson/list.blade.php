@extends('backend.master')

@section('title')
Bài học
@endsection

@section('content-header')
<div class="row">
    <div class="col-sm-2">
        <img src="{{ $course->thumbnail }}">
    </div>
    <div class="col-sm-10">
        <strong>{{ $course->title }}</strong>
        <br>
        <a href="{{ route('course', [ 'slug' => $course->slug, 'id' => $course->id ]) }}" target="_blank"><small><em>{{ route('course', [ 'slug' => $course->slug, 'id' => $course->id ]) }}</em></small></a>
        <br>
        {!! $course->description !!}
    </div>
</div>
<hr>
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Bài học</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.course.list') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
            <a href="{{ route('admin.lesson.add', [ 'course_id' => $course->id ]) }}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Thêm mới</a>
        </div>
    </div><!-- /.col -->
</div>
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
                <th>Tiêu đề</th>
                <th>Hiển thị</th>
                <th data-toggle="tooltip" title="Thứ tự trong khóa học">Thứ tự</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($list as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->title }}</td>
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input js-switch-enabled" {{ $item->enabled ? 'checked' : '' }} id="cs-enabled-{{ $item->id }}" data-id="{{ $item->id }}">
                            <label class="custom-control-label" for="cs-enabled-{{ $item->id }}"></label>
                        </div>
                    </td>
                    <td>
                        <input type="text" value="{{ $item->order_in_course }}" data-id="{{ $item->id }}" class="custom-order js-order-in-course">
                    </td>
                    <td class="text-nowrap">
                        <form action="{{ route('admin.lesson.delete', [ 'id' => $item->id, 'course_id' => $course->id ]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài học này?')">
                            @csrf
                            <a href="{{ route('admin.part.list', [ 'lesson_id' => $item->id ]) }}" class="btn btn-sm btn-info"><i class="fas fa-align-left"></i> Đầu mục</a>
                            <a href="{{ route('admin.lesson.edit', [ 'id' => $item->id, 'course_id' => $item->course_id ]) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Sửa</a>
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

        $.post('{{ route("admin.lesson.enabled") }}', {
            id: $(that).data('id'),
            enabled: $(that).prop('checked')
        }).fail(function () {
            alert('Không thể đổi trạng thái kích hoạt. Vui lòng thử lại.')
        }).always(function () {
            $(that).prop('disabled', false);
        });
    });

    $('.js-order-in-course').change(function () {
        var that = this;
        $(that).prop('disabled', true);

        $.post('{{ route("admin.lesson.order_in_course") }}', {
            id: $(that).data('id'),
            order_in_course: $(that).val()
        }).fail(function () {
            alert('Có lỗi xảy ra, vui lòng thử lại.');
        }).always(function () {
            $(that).prop('disabled', false);
        });
    });

</script>
@endsection
