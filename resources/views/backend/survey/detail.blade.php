@extends('backend.master')

@section('title')
Chi tiết phiếu khảo sát
@endsection

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Phiếu khảo sát #{{ $survey->id }}</h1>
        <i class="fas fa-calendar-alt"></i> {{ $survey->created_at }}<br/> <i class="fas fa-sync"></i> {{ $survey->updated_at }}
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <form action="{{ route('admin.survey.received', [ 'id' => $survey->id ]) }}" method="POST">
            <a href="{{ route('admin.survey.list') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
                @csrf
                @if(!$survey->is_received)
                <button type="submit" class="btn btn-outline-success">Tiếp nhận thông tin</button>
                @endif
            </form>
        </div>
    </div><!-- /.col -->
</div>
@endsection

@section('content')
    <div>
        <p><b>Họ tên: </b>{{ $survey->full_name }}</p>
        <p><b>Email: </b>{{ $survey->email }} - <b>Số điện thoại: </b>{{ $survey->phone_number }}</p>
        <p><b>Ngày sinh: </b>{{ $survey->birthday }}</p>
</div><br/>
<div class="card">
    <div class="card-header">
        <h4>Các câu hỏi</h4>
    </div>
    <div class="card-body">
        @foreach ($survey->data as $index => $item)
            <p><b>{{$index + 1}}. {{ $item['question'] }}</b></p>
            @if ($item['type'] == 'textarea')
            <p>{{ $item['answer'] ?? '' }}</p>
            @elseif ($item['type'] == 'radio')
            <p>{{ $item['options'][$item['answer']] ?? '' }}</p>
            @endif
        @endforeach
    </div>
</div>
@endsection
