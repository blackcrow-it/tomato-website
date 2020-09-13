@extends('frontend.user.master')

@section('header')
<title>Khóa học của tôi</title>
@endsection

@section('content')
<h2 class="user-page__title">Khoá học</h2>

<div class="user-page__course">
    <div class="tabJs">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tab-thongtin" role="tab" aria-controls="tab-thongtin" aria-selected="true">Thông tin</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tab-thongtin" role="tabpanel">
                <div class="table-responsive table-course">
                    <table>
                        <thead>
                            <th>Stt</th>
                            <th>Tên khoá học</th>
                            <th>Thời gian còn lại</th>
                            <th>Chi tiết</th>
                        </thead>
                        <tbody>
                            @foreach($user_courses as $item)
                                <tr>
                                    <td>{{ ($user_courses->currentPage() - 1) * $user_courses->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ $item->course->url }}" class="table-course__title">{{ $item->course->title }}</a>
                                    </td>
                                    <td>
                                        <p class="f-time"><b>{{ $item->expires_on->diffInDays() }}</b></p>
                                    </td>
                                    <td><a href="{{ $item->course->url }}" class="btn-link">Chi tiết <i class="fa fa-angle-right"></i></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $user_courses->links() }}

            </div>
        </div>
    </div>
</div>

@endsection
