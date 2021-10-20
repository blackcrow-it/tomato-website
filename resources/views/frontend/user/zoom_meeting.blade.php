@extends('frontend.user.master')

@section('header')
<title>Lớp học Zoom</title>
@endsection

@section('content')
<div class="user-page__title">Lớp học</div>

<div class="user-page__course">
    <div class="tabJs">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tab-thongtin" role="tab" aria-controls="tab-thongtin" aria-selected="true">Thông tin</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tab-thongtin" role="tabpanel">
                @if (count($user_zooms) > 0)
                <div class="table-responsive table-course">
                    <table>
                        <thead>
                            <th>Stt</th>
                            <th>Chủ đề</th>
                            <th>Thời gian học</th>
                            <th>Ca</th>
                            <th></th>
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
                                    <td><a href="{{ $item->zoomMeeting->join_url }}" class="btn-link">Tham gia <i class="fa fa-angle-right"></i></a></td>
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
        </div>
    </div>
</div>

@endsection
