@extends('frontend.master')
@section('header')
    <?php $title = '';
    if (isset($ranks)) {
        $title = 'Top điểm cao';
    } elseif (isset($list)) {
        $title = 'Bài thi';
    } elseif (isset($histories)) {
        $title = 'Lịch sử thi';
    }
    ?>
    <title>{{ $title }}</title>
@endsection
@section('body')
    <section class="section page-title">
        <div class="container">
            <nav class="breadcrumb-nav">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                </ol>
            </nav>
            <h1 class="page-title__title">Bài thi</h1>
        </div>
    </section>

    <section class="section wow" id="">
        <div class="container">
            <div class="layout layout--right">
                <div class="row">
                    <div class="col-xl-9">
                        <div class="layout-content">
                            <ul class="nav-page-exam">
                                <li>
                                    <a href="/thi-thu/bai-thi" class="@if (isset($list)) current @endif">
                                        <img src="{{ asset('tomato/assets/img/icon/icon-thithu.png') }}">
                                        <p>Thi thử</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="/thi-thu/xep-hang" class="@if (isset($ranks)) current @endif">
                                        <img src="{{ asset('tomato/assets/img/icon/icon-xephang.png') }}">
                                        <p>Xếp hạng</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="/thi-thu/lich-su" class="@if (isset($histories)) current @endif">
                                        <img src="{{ asset('tomato/assets/img/icon/icon-lichsu.png') }}">
                                        <p>Lịch sử thi</p>
                                    </a>
                                </li>
                            </ul>
                            @if (isset($list))
                                <div class="exam-wrap-box">
                                    <div class="exam-al ert">
                                        <p>Thi thử trực tuyến sẽ diễn ra vào thứ 7 hàng tuần với 2 ca thi (<b>giờ Việt
                                                Nam</b>) <span class="badge badge--primary">Sáng: 9h-11h30</span> <span
                                                class="badge badge--primary">Tối: 19h-21h30 </span></p>
                                    </div>

                                    <div class="table-chooseTheTest table-responsive">
                                        <table>
                                            <thead>
                                                <th>Bài thi</th>
                                                <th>Trình độ</th>
                                                <th>Điểm đạt</th>
                                                <th>Lịch thi</th>
                                                <th>Thời gian làm bài</th>
                                                <th>Vào thi</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($list as $item)
                                                    <?php $time = find_closest($item->shifts, $item->duration); ?>
                                                    <tr>
                                                        <td>{{ $item->title }}</td>
                                                        <td>{{ $item->level->title }}</td>
                                                        <td>{{ $item->pass_score_override }}/{{ $item->max_score_override }}
                                                        </td>
                                                        <td>{{ date('H\\hi', strtotime($time->start_time)) }}-{{ date('H\\hi', strtotime($time->end_time)) }}
                                                        </td>
                                                        <td>{{ $item->duration }} phút</td>
                                                        <?php
                                                        $now = strtotime('now');
                                                        $start = strtotime($time->start_time);
                                                        $end = strtotime($time->end_time);
                                                        ?>
                                                        @if ($start <= $now && $end >= $now)
                                                            <td>
                                                                <a href="@if (Auth::check()){{ route('practice_test.test', ['slug' => $item->slug, 'id' => $item->id]) }} @else #popup-alert-login @endif"
                                                                    class="btn-link @if (!Auth::check())show-popup-login @endif">Vào
                                                                    thi</a>
                                                            </td>
                                                        @elseif($end <= $now && ($end + $item->duration*60) >= $now)
                                                                <td><span class="f-reward" tabindex="0"
                                                                        data-toggle="tooltip" data-placement="left"
                                                                        title="Bạn đã quá {{ date('i', $now - $end) }} phút nên không thể vào thi">Quá
                                                                        giờ
                                                                        thi</span></td>
                                                            @else
                                                                <td><span>Chưa đến giờ thi</span></td>
                                                        @endif
                                                        {{-- <td><a href="#popup-alert-login" class="btn-link show-popup-login">Vào
                                                            thi</a></td> --}}
                                                    </tr>
                                                @endforeach
                                                {{-- <tr>
                                                    <td>Bài thi JLPT N2</td>
                                                    <td>Sơ cấp</td>
                                                    <td>80/100</td>
                                                    <td>9h30-11h</td>
                                                    <td>90 phút</td>
                                                    <td><a href="#popup-alert-login" class="btn-link show-popup-login">Vào
                                                            thi</a></td>
                                                </tr>
                                                <tr>
                                                    <td>Bài thi JLPT N3</td>
                                                    <td>N4</td>
                                                    <td>80/100</td>
                                                    <td>9h30-11h</td>
                                                    <td>90 phút</td>
                                                    <td><a href="#popup-alert-login" class="btn-link show-popup-login">Vào
                                                            thi</a></td>
                                                </tr>
                                                <tr>
                                                    <td>Bài thi JLPT N4</td>
                                                    <td>N5</td>
                                                    <td>80/100</td>
                                                    <td>9h30-11h</td>
                                                    <td>90 phút</td>
                                                    <td><a href="#popup-alert-login" class="btn-link show-popup-login">Vào
                                                            thi</a></td>
                                                </tr>
                                                <tr>
                                                    <td>Bài thi Tiếng trung</td>
                                                    <td>N2</td>
                                                    <td>80/100</td>
                                                    <td>9h30-11h</td>
                                                    <td>90 phút</td>
                                                    <td><span class="f-reward" tabindex="0" data-toggle="tooltip"
                                                            data-placement="left"
                                                            title="Bạn đã quá 20 phút nên không thể vào thi">Quá giờ
                                                            thi</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Bài thi Tiếng Hàn</td>
                                                    <td>N3</td>
                                                    <td>80/100</td>
                                                    <td>9h30-11h</td>
                                                    <td>90 phút</td>
                                                    <td>Kết thúc</td>
                                                </tr> --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            @endif

                            @if (isset($ranks))
                            <?php $defaultLanguage = null; ?>
                                <div class="exam-wrap-box" id="rank-content">
                                    <h2 class="exam-wrap-box__title">#TOP 100 HỌC VIÊN ĐIỂM CAO</h2>
                                    <form class="exam-filter">
                                        <div class="row">
                                            <div class="col-md-5 col-xl-4">
                                                <div class="row">
                                                    <div class="col-6 col-md-12">
                                                        <div class="input-item">
                                                            <div class="input-item__inner">
                                                                <label>Ngôn ngữ</label>
                                                                <select class="form-control">
                                                                    {{-- <option selected>Tiếng Nhật</option>
                                                                    <option>Tiếng Trung</option>
                                                                    <option>Tiếng Hàn</option> --}}
                                                                    @foreach ($languages as $key => $value)
                                                                        <option>{{ $value->title }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md-12">
                                                        <div class="input-item">
                                                            <div class="input-item__inner">
                                                                <label>Bài thi</label>
                                                                <select class="form-control">
                                                                    @foreach ($levels as $key => $value)
                                                                    <option>{{ $value->title }}</option>
                                                                    @endforeach
                                                                    {{-- <option v-for="(item, i) in levels" :key="i" v-text="item.title" v-bind:value="item.id"></option> --}}
                                                                    {{-- <option>Bài thi N3</option>
                                                                    <option>Bài thi N4</option>
                                                                    <option>Bài thi N5</option> --}}
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-7 col-xl-8">
                                                <div class="row">
                                                    <div class="col-6 col-xl-6">
                                                        <div class="input-item">
                                                            <div class="input-item__inner">
                                                                <label>Tháng</label>
                                                                <select class="form-control">
                                                                    <option>Tháng 1</option>
                                                                    <option>Tháng 2</option>
                                                                    <option>Tháng 3</option>
                                                                    <option>Tháng 4</option>
                                                                    <option>Tháng 5</option>
                                                                    <option>Tháng 6</option>
                                                                    <option>Tháng 7</option>
                                                                    <option>Tháng 8</option>
                                                                    <option>Tháng 9</option>
                                                                    <option>Tháng 10</option>
                                                                    <option>Tháng 11</option>
                                                                    <option>Tháng 12</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-xl-6">
                                                        <div class="input-item">
                                                            <div class="input-item__inner">
                                                                <label>Năm</label>  
                                                                <select class="form-control">
                                                                    <option>Năm 2020</option>
                                                                    <option>Năm 2019</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-12">
                                                        <div class="input-item">
                                                            <div class="input-item__inner">
                                                                <label>Chọn bài thi</label>
                                                                <select class="form-control">
                                                                    {{-- <option v-for="(item, i) in pts" :key="i" v-text="'Bài thi '+item.title +' '+ item.date.format('DD/MM/YYYY')" v-bind:value="i"></option> --}}
                                                                    {{-- <option>Bài thi JLPT N2 21/06/2020</option>
                                                                    <option>Bài thi JLPT N2 15/06/2020</option>
                                                                    <option>Bài thi JLPT N2 7/06/2020</option> --}}
                                                                    @foreach ($pt as $key => $value)
                                                                    <option>{{ $value->title }}</option>
                                                                @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn">Lọc kết quả</button>
                                        </div>
                                    </form>

                                    <div class="exam-alert">
                                        <p>Bạn không có trong danh sách này</p>
                                    </div>

                                    <div class="rankbox-table table-responsive">
                                        <table>
                                            <thead>
                                                <th>#Top</th>
                                                <th>Học viên</th>
                                                <th>Chi tiết</th>
                                                <th>Điểm</th>
                                                <th>Level</th>
                                                <th>Đạt</th>
                                                <th>Phần thưởng</th>
                                            </thead>
                                            <tbody>
                                                <tr class="top-1">
                                                    <td><span class="f-top"><img
                                                                src="assets/img/icon/top1.png"></span></td>
                                                    <td>
                                                        <div class="f-name">
                                                            <span class="f-name__avatar"
                                                                style="background-image: url(assets/img/image/avatar.png);"></span>
                                                            <h4 class="f-name__name">Nguyễn Quốc Khánh</h4>
                                                        </div>
                                                    </td>
                                                    <td><a href="#" class="f-btn" data-toggle="modal"
                                                            data-target="#diploma-popup">Xem kết quả</a></td>
                                                    <td>155</td>
                                                    <td>N3</td>
                                                    <td><span class="f-icon"><i class="fa fa-check"></i></span>
                                                    </td>
                                                    <td>
                                                        <span class="f-reward" tabindex="0" data-toggle="tooltip"
                                                            data-placement="left"
                                                            title="Liên hệ với bên quản lý để quy đổi sang thời gian sử dụng khoá học">+
                                                            1 tháng</span>
                                                    </td>
                                                </tr>
                                                <tr class="top-2">
                                                    <td><span class="f-top"><img
                                                                src="assets/img/icon/top2.png"></span></td>
                                                    <td>
                                                        <div class="f-name">
                                                            <span class="f-name__avatar"
                                                                style="background-image: url(assets/img/image/avatar.png);"></span>
                                                            <h4 class="f-name__name">Nguyễn Quốc Khánh</h4>
                                                        </div>
                                                    </td>
                                                    <td><a href="#" class="f-btn" data-toggle="modal"
                                                            data-target="#diploma-popup">Xem kết quả</a></td>
                                                    <td>155</td>
                                                    <td>N3</td>
                                                    <td><span class="f-icon"><i class="fa fa-check"></i></span>
                                                    </td>
                                                    <td>
                                                        <span class="f-reward" tabindex="0" data-toggle="tooltip"
                                                            data-placement="left"
                                                            title="Liên hệ với bên quản lý để quy đổi sang thời gian sử dụng khoá học">+
                                                            15 ngày</span>
                                                    </td>
                                                </tr>
                                                <tr class="top-3">
                                                    <td><span class="f-top"><img
                                                                src="assets/img/icon/top3.png"></span></td>
                                                    <td>
                                                        <div class="f-name">
                                                            <span class="f-name__avatar"
                                                                style="background-image: url(assets/img/image/avatar.png);"></span>
                                                            <h4 class="f-name__name">Nguyễn Quốc Khánh</h4>
                                                        </div>
                                                    </td>
                                                    <td><a href="#" class="f-btn" data-toggle="modal"
                                                            data-target="#diploma-popup">Xem kết quả</a></td>
                                                    <td>155</td>
                                                    <td>N3</td>
                                                    <td><span class="f-icon"><i class="fa fa-check"></i></span>
                                                    </td>
                                                    <td>
                                                        <span class="f-reward" tabindex="0" data-toggle="tooltip"
                                                            data-placement="left"
                                                            title="Liên hệ với bên quản lý để quy đổi sang thời gian sử dụng khoá học">+
                                                            10 ngày</span>
                                                    </td>
                                                </tr>
                                                <tr class="top-4-10">
                                                    <td><span class="f-top">04</td>
                                                    <td>
                                                        <div class="f-name">
                                                            <span class="f-name__avatar"
                                                                style="background-image: url(assets/img/image/avatar.png);"></span>
                                                            <h4 class="f-name__name">Nguyễn Quốc Khánh</h4>
                                                        </div>
                                                    </td>
                                                    <td><a href="#" class="f-btn" data-toggle="modal"
                                                            data-target="#diploma-popup">Xem kết quả</a></td>
                                                    <td>155</td>
                                                    <td>N3</td>
                                                    <td><span class="f-icon"><i class="fa fa-check"></i></span>
                                                    </td>
                                                    <td>0</td>
                                                </tr>
                                                <tr class="top-4-10">
                                                    <td><span class="f-top">05</td>
                                                    <td>
                                                        <div class="f-name">
                                                            <span class="f-name__avatar"
                                                                style="background-image: url(assets/img/image/avatar.png);"></span>
                                                            <h4 class="f-name__name">Nguyễn Quốc Khánh</h4>
                                                        </div>
                                                    </td>
                                                    <td><a href="#" class="f-btn" data-toggle="modal"
                                                            data-target="#diploma-popup">Xem kết quả</a></td>
                                                    <td>155</td>
                                                    <td>N3</td>
                                                    <td><span class="f-icon"><i class="fa fa-check"></i></span>
                                                    </td>
                                                    <td>0</td>
                                                </tr>
                                                <tr class="top-4-10">
                                                    <td><span class="f-top">06</td>
                                                    <td>
                                                        <div class="f-name">
                                                            <span class="f-name__avatar"
                                                                style="background-image: url(assets/img/image/avatar.png);"></span>
                                                            <h4 class="f-name__name">Nguyễn Quốc Khánh</h4>
                                                        </div>
                                                    </td>
                                                    <td><a href="#" class="f-btn" data-toggle="modal"
                                                            data-target="#diploma-popup">Xem kết quả</a></td>
                                                    <td>155</td>
                                                    <td>N3</td>
                                                    <td><span class="f-icon"><i class="fa fa-check"></i></span>
                                                    </td>
                                                    <td>0</td>
                                                </tr>
                                                <tr class="top-4-10">
                                                    <td><span class="f-top">07</td>
                                                    <td>
                                                        <div class="f-name">
                                                            <span class="f-name__avatar"
                                                                style="background-image: url(assets/img/image/avatar.png);"></span>
                                                            <h4 class="f-name__name">Nguyễn Quốc Khánh</h4>
                                                        </div>
                                                    </td>
                                                    <td><a href="#" class="f-btn" data-toggle="modal"
                                                            data-target="#diploma-popup">Xem kết quả</a></td>
                                                    <td>155</td>
                                                    <td>N3</td>
                                                    <td><span class="f-icon"><i class="fa fa-check"></i></span>
                                                    </td>
                                                    <td>0</td>
                                                </tr>
                                                <tr class="top-4-10">
                                                    <td><span class="f-top">08</td>
                                                    <td>
                                                        <div class="f-name">
                                                            <span class="f-name__avatar"
                                                                style="background-image: url(assets/img/image/avatar.png);"></span>
                                                            <h4 class="f-name__name">Nguyễn Quốc Khánh</h4>
                                                        </div>
                                                    </td>
                                                    <td><a href="#" class="f-btn" data-toggle="modal"
                                                            data-target="#diploma-popup">Xem kết quả</a></td>
                                                    <td>155</td>
                                                    <td>N3</td>
                                                    <td><span class="f-icon"><i class="fa fa-check"></i></span>
                                                    </td>
                                                    <td>0</td>
                                                </tr>
                                                <tr class="top-4-10">
                                                    <td><span class="f-top">09</td>
                                                    <td>
                                                        <div class="f-name">
                                                            <span class="f-name__avatar"
                                                                style="background-image: url(assets/img/image/avatar.png);"></span>
                                                            <h4 class="f-name__name">Nguyễn Quốc Khánh</h4>
                                                        </div>
                                                    </td>
                                                    <td><a href="#" class="f-btn" data-toggle="modal"
                                                            data-target="#diploma-popup">Xem kết quả</a></td>
                                                    <td>155</td>
                                                    <td>N3</td>
                                                    <td><span class="f-icon"><i class="fa fa-check"></i></span>
                                                    </td>
                                                    <td>0</td>
                                                </tr>
                                                <tr class="top-4-10">
                                                    <td><span class="f-top">10</td>
                                                    <td>
                                                        <div class="f-name">
                                                            <span class="f-name__avatar"
                                                                style="background-image: url(assets/img/image/avatar.png);"></span>
                                                            <h4 class="f-name__name">Nguyễn Quốc Khánh</h4>
                                                        </div>
                                                    </td>
                                                    <td><a href="#" class="f-btn" data-toggle="modal"
                                                            data-target="#diploma-popup">Xem kết quả</a></td>
                                                    <td>155</td>
                                                    <td>N3</td>
                                                    <td><span class="f-icon"><i class="fa fa-check"></i></span>
                                                    </td>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="f-top">11</td>
                                                    <td>
                                                        <div class="f-name">
                                                            <span class="f-name__avatar"
                                                                style="background-image: url(assets/img/image/avatar.png);"></span>
                                                            <h4 class="f-name__name">Nguyễn Quốc Khánh</h4>
                                                        </div>
                                                    </td>
                                                    <td><a href="#" class="f-btn" data-toggle="modal"
                                                            data-target="#diploma-popup">Xem kết quả</a></td>
                                                    <td>155</td>
                                                    <td>N3</td>
                                                    <td><span class="f-icon"><i class="fa fa-check"></i></span>
                                                    </td>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="f-top">12</td>
                                                    <td>
                                                        <div class="f-name">
                                                            <span class="f-name__avatar"
                                                                style="background-image: url(assets/img/image/avatar.png);"></span>
                                                            <h4 class="f-name__name">Nguyễn Quốc Khánh</h4>
                                                        </div>
                                                    </td>
                                                    <td><a href="#" class="f-btn" data-toggle="modal"
                                                            data-target="#diploma-popup">Xem kết quả</a></td>
                                                    <td>155</td>
                                                    <td>N3</td>
                                                    <td><span class="f-icon"><i class="fa fa-check"></i></span>
                                                    </td>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="f-top">13</td>
                                                    <td>
                                                        <div class="f-name">
                                                            <span class="f-name__avatar"
                                                                style="background-image: url(assets/img/image/avatar.png);"></span>
                                                            <h4 class="f-name__name">Nguyễn Quốc Khánh</h4>
                                                        </div>
                                                    </td>
                                                    <td><a href="#" class="f-btn" data-toggle="modal"
                                                            data-target="#diploma-popup">Xem kết quả</a></td>
                                                    <td>155</td>
                                                    <td>N3</td>
                                                    <td><span class="f-icon"><i class="fa fa-check"></i></span>
                                                    </td>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="f-top">14</td>
                                                    <td>
                                                        <div class="f-name">
                                                            <span class="f-name__avatar"
                                                                style="background-image: url(assets/img/image/avatar.png);"></span>
                                                            <h4 class="f-name__name">Nguyễn Quốc Khánh</h4>
                                                        </div>
                                                    </td>
                                                    <td><a href="#" class="f-btn" data-toggle="modal"
                                                            data-target="#diploma-popup">Xem kết quả</a></td>
                                                    <td>155</td>
                                                    <td>N3</td>
                                                    <td><span class="f-icon"><i class="fa fa-check"></i></span>
                                                    </td>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="f-top">15</td>
                                                    <td>
                                                        <div class="f-name">
                                                            <span class="f-name__avatar"
                                                                style="background-image: url(assets/img/image/avatar.png);"></span>
                                                            <h4 class="f-name__name">Nguyễn Quốc Khánh</h4>
                                                        </div>
                                                    </td>
                                                    <td><a href="#" class="f-btn" data-toggle="modal"
                                                            data-target="#diploma-popup">Xem kết quả</a></td>
                                                    <td>155</td>
                                                    <td>N3</td>
                                                    <td><span class="f-icon"><i class="fa fa-close"></i></span>
                                                    </td>
                                                    <td>0</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if (isset($histories))
                                <div class="exam-wrap-box">
                                    <form class="exam-filter">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="input-item">
                                                    <div class="input-item__inner">
                                                        <label>Ngôn ngữ</label>
                                                        <select class="form-control">
                                                            @foreach ($languages as $key => $value)
                                                                <option @if ($key === 0) selected @endif>{{ $value->title }}
                                                                </option>
                                                            @endforeach
                                                            {{-- <option selected>Tiếng Nhật</option>
                                                            <option>Tiếng Trung</option>
                                                            <option>Tiếng Hàn</option> --}}
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4">
                                                <div class="input-item">
                                                    <div class="input-item__inner">
                                                        <label>Tháng</label>
                                                        <select class="form-control">
                                                            <option>Tháng 1</option>
                                                            <option>Tháng 2</option>
                                                            <option>Tháng 3</option>
                                                            <option>Tháng 4</option>
                                                            <option>Tháng 5</option>
                                                            <option selected>Tháng 6</option>
                                                            <option>Tháng 7</option>
                                                            <option>Tháng 8</option>
                                                            <option>Tháng 9</option>
                                                            <option>Tháng 10</option>
                                                            <option>Tháng 11</option>
                                                            <option>Tháng 12</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4">
                                                <div class="input-item">
                                                    <div class="input-item__inner">
                                                        <label>Năm</label>
                                                        <select class="form-control">
                                                            <option>Năm 2020</option>
                                                            <option>Năm 2019</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn">Lọc kết quả</button>
                                        </div>
                                    </form>

                                    <div class="exam-alert">
                                        <p>Không có lịch sử thi ( trường hợp không có sẽ hiện box này )</p>
                                    </div>

                                    <div class="table-historyExam table-responsive">
                                        <table>
                                            <thead>
                                                <th>Stt</th>
                                                <th>Bài thi</th>
                                                <th>Ngày thi</th>
                                                <th>Điểm</th>
                                                <th>Top</th>
                                                <th>Đạt</th>
                                                <th>Chi tiết</th>
                                                <th>Phần thưởng</th>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td class="f-name">Bài thi JLPT <b>N2</b></td>
                                                    <td>30/06/2020</td>
                                                    <td>155</td>
                                                    <td><span class="f-top"><img
                                                                src="assets/img/icon/top1.png"></span></td>
                                                    <td><span class="f-icon"><i class="fa fa-check"></i></span>
                                                    </td>
                                                    <td><a href="#" class="f-btn" data-toggle="modal"
                                                            data-target="#diploma-popup">Xem kết quả</a></td>
                                                    <td>
                                                        <span class="f-reward" tabindex="0" data-toggle="tooltip"
                                                            data-placement="left"
                                                            title="Liên hệ với bên quản lý để quy đổi sang thời gian sử dụng khoá học">+
                                                            1 tháng</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td class="f-name">Bài thi JLPT <b>N2</b></td>
                                                    <td>21/06/2020</td>
                                                    <td>155</td>
                                                    <td><span class="f-top"><img
                                                                src="assets/img/icon/top2.png"></span></td>
                                                    <td><span class="f-icon"><i class="fa fa-check"></i></span>
                                                    </td>
                                                    <td><a href="#" class="f-btn" data-toggle="modal"
                                                            data-target="#diploma-popup">Xem kết quả</a></td>
                                                    <td>
                                                        <span class="f-reward" tabindex="0" data-toggle="tooltip"
                                                            data-placement="left"
                                                            title="Liên hệ với bên quản lý để quy đổi sang thời gian sử dụng khoá học">+
                                                            15 ngày</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td class="f-name">Bài thi JLPT <b>N2</b></td>
                                                    <td>15/06/2020</td>
                                                    <td>155</td>
                                                    <td><span class="f-top"><img
                                                                src="assets/img/icon/top3.png"></span></td>
                                                    <td><span class="f-icon"><i class="fa fa-check"></i></span>
                                                    </td>
                                                    <td><a href="#" class="f-btn" data-toggle="modal"
                                                            data-target="#diploma-popup">Xem kết quả</a></td>
                                                    <td>
                                                        <span class="f-reward" tabindex="0" data-toggle="tooltip"
                                                            data-placement="left"
                                                            title="Liên hệ với bên quản lý để quy đổi sang thời gian sử dụng khoá học">+
                                                            10 ngày</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>4</td>
                                                    <td class="f-name">Bài thi JLPT <b>N2</b></td>
                                                    <td>7/06/2020</td>
                                                    <td>155</td>
                                                    <td>50</td>
                                                    <td><span class="f-icon"><i class="fa fa-check"></i></span>
                                                    </td>
                                                    <td><a href="#" class="f-btn" data-toggle="modal"
                                                            data-target="#diploma-popup">Xem kết quả</a></td>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <td>5</td>
                                                    <td class="f-name">Bài thi JLPT <b>N2</b></td>
                                                    <td>1/06/2020</td>
                                                    <td>30</td>
                                                    <td>Không</td>
                                                    <td><span class="f-icon"><i class="fa fa-close"></i></span>
                                                    </td>
                                                    <td><a href="#" class="f-btn" data-toggle="modal"
                                                            data-target="#diploma-popup">Xem kết quả</a></td>
                                                    <td>0</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="col-xl-3">
                        <div class="layout-sidebar">
                            <div class="widget widget--course">
                                <h2 class="widget__title">Khoá học</h2>

                                <ul>
                                    <li class="item">
                                        <a href="#">
                                            <span class="item__icon"><img src="assets/img/icon/icon-china.svg"></span>
                                            <h3 class="item__title">Khoá học tiếng Trung</h3>
                                        </a>
                                    </li>
                                    <li class="item">
                                        <a href="#">
                                            <span class="item__icon"><img src="assets/img/icon/icon-korea.svg"></span>
                                            <h3 class="item__title">Khoá học tiếng Hàn</h3>
                                        </a>
                                    </li>
                                    <li class="item">
                                        <a href="#">
                                            <span class="item__icon"><img src="assets/img/icon/icon-japan.svg"></span>
                                            <h3 class="item__title">Khoá học tiếng Nhật</h3>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="widget widget--book">
                                <h2 class="widget__title">Tài liệu học</h2>
                                <div class="owl-carousel">
                                    <a href="chitietsach.html" class="item">
                                        <div class="item__img"><img src="assets/img/image/bookBox-1.jpg"></div>
                                        <h3 class="item__title">Giáo trình hán ngữ</h3>
                                        <div class="item__price">
                                            <ins>499.000đ</ins>
                                            <del>899.000đ</del>
                                        </div>
                                    </a>
                                    <a href="chitietsach.html" class="item">
                                        <div class="item__img"><img src="assets/img/image/bookBox-1.jpg"></div>
                                        <h3 class="item__title">Giáo trình hán ngữ</h3>
                                        <div class="item__price">
                                            <ins>499.000đ</ins>
                                            <del>899.000đ</del>
                                        </div>
                                    </a>
                                    <a href="chitietsach.html" class="item">
                                        <div class="item__img"><img src="assets/img/image/bookBox-1.jpg"></div>
                                        <h3 class="item__title">Giáo trình hán ngữ</h3>
                                        <div class="item__price">
                                            <ins>499.000đ</ins>
                                            <del>899.000đ</del>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section bg-gray" id="consultationForm">
        <div class="container">
            <div class="consultationForm">
                <div class="row no-gutters">
                    <div class="col-md-6">
                        <div class="consultationForm__content">
                            <div class="consultationForm__fix">
                                <h2 class="consultationForm__title">Đăng ký nhận tin</h2>
                                <form class="consultationForm__form">
                                    <div class="input-item">
                                        <div class="input-item__inner">
                                            <input type="text" name="name" placeholder="Họ và tên" class="form-control">
                                        </div>
                                    </div>
                                    <div class="input-item">
                                        <div class="input-item__inner">
                                            <input type="text" name="phone" placeholder="Số điện thoại"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="input-item">
                                        <div class="input-item__inner">
                                            <input type="text" name="email" placeholder="Email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="input-item">
                                        <div class="input-item__inner">
                                            <select class="form-control" name="course">
                                                <option>Khoá học tiếng Hàn</option>
                                                <option>Khoá học tiếng Trung</option>
                                                <option>Khoá học tiếng Nhật</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="input-item">
                                        <div class="input-item__inner">
                                            <textarea type="text" name="name" placeholder="Nội dung"
                                                class="form-control"></textarea>
                                        </div>
                                    </div>

                                    <div class="button-item">
                                        <button type="submit" class="btn">Nhận tư vấn</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="consultationForm__bg"
                            style="background-image: url(assets/img/image/consultationForm-bg.jpg);"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('modal')
    @if (!Auth::check())
        <div class="modal fade" id="popup-alert-login" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <button type="button" class="modal-close" data-dismiss="modal"><i
                            class="fa fa-close"></i></button>

                    <h2 class="popup-alert-login__title">Bạn cần phải đăng nhập để thi thử</h2>

                    <div class="popup-alert-login__btn">
                        <a href="{{ route('login') }}" class="btn">Đăng nhập</a>
                        <a href="{{ route('register') }}" class="btn btn--secondary">Đăng ký tài khoản</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('script')
    <script>
    @if(isset($ranks))
    new Vue({
        el: '#rank-content',
            data: {
               languageId: "{{ isset($defaultLanguage) ? $defaultLanguage : 'undefined' }}",
               levels:[],
               levelId: null,
               params: {levelId: null, year: null, month: null, pt: 0},
               pts:[]
            },
            mounted() {
                // console.log('acascc')
                // let self = this;
                // self.getLevel(this.languageId)
                // .then((e)=>{
                //     self.getPracticeTests(self.params.levelId).then((x)=>{
                //         let result =[];
                //         x.pts.forEach(function(s){
                //             let dates = self.getDates(new Date(s.created_at), new Date())
                //             if(s.loop){
                //                 let tempD = dates.filter((d)=> s.loop_days.includes(moment(d).isoWeekday(d.day()+1).day()))
                //                 tempD.forEach(function(dd){
                //                     result.push({'title': s.title, id: s.id, date: dd})
                //                 })
                //             }
                //         })
                //         self.pts = result.sort((a,b) => moment(a).valueOf() - moment(b).valueOf()).reverse();;
                //     })
                // });    
            },
            methods: {
                getDates: function (startDate, endDate) {
                    let dateArr = [];
                    var start = new Date(startDate);
                    var end = new Date(endDate);
                    while(start < end){
                        dateArr.push(moment(start));
                        var newDate = start.setDate(start.getDate() + 1);
                        start = new Date(newDate); 
                    }
                    return dateArr;
                },

                getLevel:function(id){
                    let self = this;
                    return new Promise((resolve, reject) =>{
                        axios.get('{{ route('practice_test.rank.level') }}', {params: {id: id}}).then((response)=>{
                        console.log(response)
                        self.levels = response.levels;
                        if(self.levels[0]){
                            self.params.levelId = self.levels[0]['id']
                        }
                        resolve(response);
                    }).catch(e => {
                        console.log('Fail')
                        reject(e);
                    });
                });
            },

            getPracticeTests: function(id){
                    let self = this;
                    return new Promise((resolve, reject) =>{
                        axios.get('{{ route('practice_test.rank.pt') }}', {params: {id: id}}).then((response)=>{

                        resolve(response);
                    }).catch(e => {
                        console.log('Fail')
                        reject(e);
                    });
                })
            }
        },
    })
    @endif
    </script>
@endsection
