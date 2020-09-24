@extends('backend.master')

@section('title')
Đầu mục
@endsection

@section('content-header')
<div class="row">
    <div class="col-sm-2">
        <img src="{{ $course->thumbnail }}">
    </div>
    <div class="col-sm-5">
        <strong>{{ $course->title }}</strong>
        <br>
        <a href="{{ route('course', [ 'slug' => $course->slug ]) }}" target="_blank"><small><em>{{ route('course', [ 'slug' => $course->slug ]) }}</em></small></a>
        <br>
        {{ $course->description }}
    </div>
    <div class="col-sm-5">
        <strong>{{ $lesson->title }}</strong>
    </div>
</div>
<hr>
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Đầu mục</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.lesson.list', [ 'course_id' => $course->id ]) }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
            <a href="{{ route('admin.part.add', [ 'lesson_id' => $lesson->id ]) }}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Thêm mới</a>
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
                <th>Loại</th>
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
                        @switch($item->type)
                            @case(\App\Constants\PartType::VIDEO)
                                <i class="fas fa-video"></i>
                                Video
                                @break
                            @case(\App\Constants\PartType::YOUTUBE)
                                <i class="fab fa-youtube"></i>
                                Youtube
                                @break
                            @case(\App\Constants\PartType::CONTENT)
                                <i class="fas fa-file-alt"></i>
                                Bài viết
                                @break
                            @case(\App\Constants\PartType::TEST)
                                <i class="fas fa-question-circle"></i>
                                Trắc nghiệm
                                @break
                            @case(\App\Constants\PartType::SURVEY)
                                <i class="fas fa-poll"></i>
                                Khảo sát
                                @break
                        @endswitch
                    </td>
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input js-switch-enabled" {{ $item->enabled ? 'checked' : '' }} id="cs-enabled-{{ $item->id }}" data-id="{{ $item->id }}">
                            <label class="custom-control-label" for="cs-enabled-{{ $item->id }}"></label>
                        </div>
                    </td>
                    <td>
                        <input type="text" value="{{ $item->order_in_lesson }}" data-id="{{ $item->id }}" class="custom-order js-order-in-lesson">
                    </td>
                    <td class="text-nowrap">
                        <form action="{{ route('admin.part_' . $item->type . '.delete', [ 'part_id' => $item->id, 'lesson_id' => $lesson->id ]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đầu mục này?')">
                            @csrf
                            <a href="{{ route('admin.part_' . $item->type . '.edit', [ 'part_id' => $item->id, 'lesson_id' => $item->lesson_id ]) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Sửa</a>
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

        $.post('{{ route("admin.part.enabled") }}', {
            id: $(that).data('id'),
            enabled: $(that).prop('checked')
        }).fail(function () {
            alert('Không thể đổi trạng thái kích hoạt. Vui lòng thử lại.')
        }).always(function () {
            $(that).prop('disabled', false);
        });
    });

    $('.js-order-in-lesson').change(function () {
        var that = this;
        $(that).prop('disabled', true);

        $.post('{{ route("admin.part.order_in_lesson") }}', {
            id: $(that).data('id'),
            order_in_lesson: $(that).val()
        }).fail(function () {
            alert('Có lỗi xảy ra, vui lòng thử lại.');
        }).always(function () {
            $(that).prop('disabled', false);
        });
    });

</script>
@endsection
