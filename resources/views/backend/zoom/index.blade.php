@extends('backend.master')

@section('title')
Lịch học Zoom
@endsection

@section('content-header')<div class="row mb-2">
<div class="col-sm-6">
        <h1 class="m-0 text-dark">Lịch học Zoom</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.zoom.index_user') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Tài khoản</a>
            <a href="{{ route('admin.zoom.new', ['id' => $id]) }}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Lên lịch</a>
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
<div class="row" id="zoom__list">
    @if(count($data) <= 0)
    <div class="col-lg-12">
        <div style="padding-bottom: 10px;">
            Chưa có phòng học nào được tạo <a href="{{ route('admin.zoom.new', ['id' => $id]) }}"><i class="fas fa-plus"></i> Thêm mới</a>
        </div>
    </div>
    @else
        @foreach ($data as $item)
        <div class="col-lg-4 d-flex align-items-stretch">
            <div class="card card-primary" style="width: 100%">
                <div class="card-header">
                    <h3 class="card-title">ID cuộc họp: {{ $item['meeting_id'] }}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-toggle="modal" data-target="#modal-delete-{{ $item['meeting_id'] }}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body text-center">
                    <div class="card-title float-none" style="font-size: 1.5rem;">
                        <div class="topic__zoom">
                            <a href="{{ route('admin.zoom.show', ['id' => $item['meeting_id']]) }}" class="link-primary">{{ $item['topic'] }}</a>
                        </div>
                    </div>
                    <div class="card-text">
                        <ul class="list-group list-group-flush">
                            @if ($item['type'] == 2)
                                <li class="list-group-item">Bắt đầu ngày <u>{{ date("d/m/Y", strtotime($item['start_time'].' UTC')) }}</u></li>
                                <li class="list-group-item">{{ date("h:i A", strtotime($item['start_time'].' UTC')) }} - {{ date("h:i A", strtotime('+'.$item['duration'].' minutes', strtotime($item['start_time'].' UTC'))) }}</li>
                            @elseif ($item['type'] == 8)
                                @if ($item['occurrences'])
                                @php
                                    $occurrences = json_decode($item['occurrences'], true)
                                @endphp
                                @if ($occurrences)
                                <li class="list-group-item">Từ <u>{{ date("d/m/Y", strtotime($occurrences[0]['start_time'].' UTC')) }}</u> đến <u>{{ date("d/m/Y", strtotime($occurrences[count($occurrences) - 1]['start_time'].' UTC')) }}</u></li>
                                <li class="list-group-item">{{ date("h:i A", strtotime($occurrences[0]['start_time'].' UTC')) }} - {{ date("h:i A", strtotime('+'.$occurrences[0]['duration'].' minutes', strtotime($occurrences[0]['start_time'].' UTC'))) }} ({{ count($occurrences) }} buổi) </li>
                                @else
                                <li class="list-group-item">Không có buổi học nào</li>
                                @endif
                                @endif
                            @endif
                            <li class="list-group-item">{{ ($item['agenda']) ? $item['agenda'] : 'Không có mô tả ...' }}</li>
                        </ul>
                    </div>
                </div>
                <div class="card-footer">
                    <small class="text-muted">{{ AppHelper::instance()->nicetime($item['created_at']) }}</small>
                    @if ($item['is_start'])
                    <a href="{{ $item['join_url'] }}" target="_blank" class="btn btn-sm btn-primary float-right" style="margin-left: 10px;"><i class="fas fa-door-open"></i> Tham gia</a>
                    <a href="#" target="_blank" class="btn btn-sm btn-danger disabled float-right"><i class="fas fa-video"></i> Đang diễn ra</a>
                    @else
                    <a href="{{ $item['join_url'] }}" target="_blank" class="btn btn-sm btn-primary disabled float-right" style="margin-left: 10px;"><i class="fas fa-door-open"></i> Tham gia</a>
                    <a href="{{ $item['start_url'] }}" target="_blank" class="btn btn-sm btn-danger float-right"><i class="fas fa-video"></i> Bắt đầu</a>
                    @endif

                </div>
            </div>
            <div class="modal fade" id="modal-delete-{{ $item['meeting_id'] }}" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title">Xoá phòng học</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xoá phòng học này không?</p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Huỷ</button>
                        <form id="form__delete_zoom-{{ $item['meeting_id'] }}" class="form__delete_zoom" action="{{ route('admin.zoom.destroy', ['id' => $item['meeting_id']]) }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-outline-danger submit__delete__zoom">Xoá</button>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection
@section('script')
<script>
    const vueCourse = new Vue({
        el: '#zoom__list',
        mounted() {
            // moment().locale('vi');
        },
    })
</script>
<script>
    $(document).ready(function () {
        $(".form__delete_zoom").submit(function (e) {
            $(this).find(':input[type=submit]').prop('disabled', true);
        });
    });
</script>
@endsection
