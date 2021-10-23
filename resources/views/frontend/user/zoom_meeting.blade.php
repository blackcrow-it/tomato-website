@extends('frontend.user.master')

@section('header')
<title>Lớp học trực tuyến</title>
@endsection

@section('content')
<div class="user-page__title">Lớp học trực tuyến</div>

<div class="user-page__course">
    <div class="tabJs">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tab-thongtin" role="tab" aria-controls="tab-thongtin" aria-selected="true">Thông tin</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-huongdan" role="tab" aria-controls="tab-huongdan" aria-selected="true">Hướng dẫn</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tab-thongtin" role="tabpanel">
                <div class="">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $msg)
                                    <li>{{ $msg }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">
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
                </div>
                @if (count($user_zooms) > 0)
                <div class="table-responsive table-course">
                    <table>
                        <thead>
                            <th>Stt</th>
                            <th>Chủ đề</th>
                            <th>Thời gian học</th>
                            <th>Ca</th>
                            <th>Trạng thái</th>
                        </thead>
                        <tbody>
                            @foreach($user_zooms as $item)
                                <tr>
                                    <td>{{ ($user_zooms->currentPage() - 1) * $user_zooms->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ $item->zoomMeeting->join_url }}" class="table-course__title">{{ $item->zoomMeeting->topic }}</a><br/>
                                        <i>{{ $item->zoomMeeting->agenda }}</i>
                                    </td>
                                    @if ($item->zoomMeeting->type == 2)
                                    <td>
                                        <p class="f-time"><b>{{ date("d/m/Y", strtotime($item->zoomMeeting->start_time)) }}</b></p>
                                    </td>
                                    <td>
                                        <p class="f-time"><b>{{ date("h:i A", strtotime($item->zoomMeeting->start_time)) }} - {{ date("h:i A", strtotime('+'.$item->zoomMeeting->duration.' minutes', strtotime($item->zoomMeeting->start_time))) }}</b></p>
                                    </td>
                                    @else
                                        @php
                                            $occurrences = json_decode($item->zoomMeeting->occurrences, true)
                                        @endphp
                                        @if ($occurrences)
                                        <td>
                                            <p class="f-time"><b>{{ date("d/m/Y", strtotime($occurrences[0]['start_time'])) }} - {{ date("d/m/Y", strtotime($occurrences[count($occurrences) - 1]['start_time'])) }}</b><br/>({{ count($occurrences) }} buổi)</p>
                                        </td>
                                        <td>
                                            <p class="f-time"><b>{{ date("h:i A", strtotime($occurrences[0]['start_time'])) }} - {{ date("h:i A", strtotime('+'.$occurrences[0]['duration'].' minutes', strtotime($occurrences[0]['start_time']))) }}</b></p>
                                        </td>
                                        @endif
                                    @endif
                                    <td>
                                        @if ($item->zoomMeeting->is_start)
                                        <a href="{{ route('zoom', ['meeting_id' => $item->zoomMeeting->meeting_id]) }}" class="btn-link">Tham gia <i class="fa fa-angle-right"></i></a>
                                        @else
                                        Chưa bắt đầu
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $user_zooms->links() }}
                @else
                <br/>
                <div style="text-align: center">Bạn chưa được thêm vào lớp học nào cả.</div>
                @endif
            </div>
            <div class="tab-pane fade" id="tab-huongdan" role="tabpanel"></div>
        </div>
    </div>
</div>

@endsection
