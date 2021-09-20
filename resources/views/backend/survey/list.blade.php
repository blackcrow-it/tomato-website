@extends('backend.master')

@section('title')
Khảo sát
@endsection

@section('content-header')
<h1>Khảo sát</h1><br />
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
                <th>Tên học viên</th>
                <th>Trong khoá học</th>
                <th>Đã mua khoá</th>
                <th>Trạng thái</th>
                <th>Thời gian</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($list as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->full_name }}</td>
                    <td><a href="{{ route('admin.lesson.list', [ 'course_id' => $item->part->lesson->course->id ]) }}" target="_blank">{{ $item->part->lesson->course->title }}</a></td>
                    <td>
                        @if ($item->is_student)
                        <i class="fa fa-check" aria-hidden="true" style="color: green" data-toggle="tooltip" title="Đã mua"></i>
                        @else
                        <i class="fa fa-times" aria-hidden="true" style="color: red" data-toggle="tooltip" title="Học thử"></i>
                        @endif
                    </td>
                    <td>
                        @if ($item->is_received)
                        <i class="fa fa-check" aria-hidden="true" data-toggle="tooltip" title="Đã duyệt bởi {{ $item->received_by->name }}" style="color: green"></i>
                        @else
                            @if ($item->is_read)
                            <i class="fa fa-eye" aria-hidden="true" data-toggle="tooltip" title="Đã xem"></i>
                            @else
                            <i class="fa fa-eye-slash" aria-hidden="true" data-toggle="tooltip" title="Chưa xem"></i>
                            @endif
                        @endif
                    </td>
                    <td>{{ $item->created_at }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('admin.survey.detail', [ 'id' => $item->id ]) }}" class="btn btn-sm btn-info">Chi tiết</a>
                        <a href="{{ route('admin.survey.statistic', [ 'partId' => $item->part_id ]) }}" class="btn btn-sm btn-info"><i class="fas fa-chart-pie"></i> Thống kê</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
{{ $list->withQueryString()->links() }}
</div>
@endsection
