@extends('backend.master')
@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Đề thi thử</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.practice_test.add') }}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Thêm mới</a>
        </div>
    </div><!-- /.col -->
</div>
@endsection
@section('content')
<div class="table-responsive">
    <table class="table table-hover table-light">
        <thead class="bg-lightblue">
            <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Ngày thi</th>
                <th>Giờ thi</th>
                <th>Thời gian làm bài</th>
                <th>Câu hỏi</th>
                <th>Lặp lại</th>
                <th>Hiển thị</th>
                @if(request()->input('filter.category_id'))
                    <th data-toggle="tooltip" title="Thứ tự trong danh mục">Thứ tự</th>
                @elseif(request()->input('filter.position'))
                    <th data-toggle="tooltip" title="Thứ tự hiển thị">Thứ tự</th>
                @endif
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($list as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->loop? date('d-m-Y', get_next_day($item->shifts->all(), $item->loop_days)): date('d-m-Y', strtotime($item->date))}}</td>
                    <td>{{ date('h:i', strtotime($item->start_time)) }} - {{date('h:i', strtotime($item->end_time))}}</td>
                    <td>{{$item->duration}} phút</td>
                    <td>{{count($item->questions)}} câu</td>
                    <td> <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input js-switch-loop" {{ $item->loop ? 'checked' : '' }} id="cs-loop-{{ $item->id }}" data-id="{{ $item->id }}">
                        <label class="custom-control-label" for="cs-loop-{{ $item->id }}"></label>
                    </div></td>
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input js-switch-enabled" {{ $item->enabled ? 'checked' : '' }} id="cs-enabled-{{ $item->id }}" data-id="{{ $item->id }}">
                            <label class="custom-control-label" for="cs-enabled-{{ $item->id }}"></label>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection