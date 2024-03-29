@extends('backend.master')

@section('title')
Phiếu khảo sát
@endsection

@section('content-header')
<h1>Phiếu khảo sát</h1><br />
@endsection

@section('content')
<div class="table-responsive">
    <table class="table table-hover table-light">
        <thead class="bg-lightblue">
            <tr>
                <th>ID</th>
                <th>Tên phiếu</th>
                <th>Trong khoá học</th>
                <th>Thời gian</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($list as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td><a href="{{ route('admin.part.list', [ 'lesson_id' => $item->lesson->id ]) }}">{{ $item->title }}</a> ({{ $item->lesson->title }})</td>
                    <td><a href="{{ route('admin.lesson.list', [ 'course_id' => $item->lesson->course->id ]) }}" target="_blank">{{ $item->lesson->course->title }}</a></td>
                    <td>{{ $item->created_at }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('admin.survey.statistic', [ 'partId' => $item->id ]) }}" class="btn btn-sm btn-info"><i class="fas fa-chart-pie"></i> Thống kê</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
{{ $list->withQueryString()->links() }}
</div>
@endsection
