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
                                                                <select class="form-control" v-model="params.languageId" @change="changeLanguage()">
                                                                    {{-- <option selected>Tiếng Nhật</option>
                                                                    <option>Tiếng Trung</option>
                                                                    <option>Tiếng Hàn</option> --}}
                                                                    @foreach ($languages as $key => $value)
                                                                    <?php if ($key == 0){
                                                                        $defaultLanguage = $value->id;
                                                                    }?>
                                                                        <option v-bind:value="{{$value->id}}">
                                                                            {{ $value->title }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md-12">
                                                        <div class="input-item">
                                                            <div class="input-item__inner">
                                                                <label>Bài thi</label>
                                                                <select class="form-control" v-model="params.levelId" @change="changeType($event)">
                                                                    <option v-for="(item, i) in levels" :key="i" v-text="item.title" v-bind:value="item.id"></option>
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
                                                                <select class="form-control" v-model="params.month" @change="onChangeTime($event)">
                                                                    <option v-bind:value="index" v-for="index in 12" :key="index" v-text="'Tháng '+ index"></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-xl-6">
                                                        <div class="input-item">
                                                            <div class="input-item__inner">
                                                                <label>Năm</label>  
                                                                <select class="form-control"  @change="onChangeTime($event)"  v-model="params.year">
                                                                    <option v-bind:value="item" v-for="(item, i) in years" :key="i" v-text="'Năm '+item"></option>
                                                                    {{-- <option>Năm 2019</option> --}}
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-12">
                                                        <div class="input-item">
                                                            <div class="input-item__inner">
                                                                <label>Chọn bài thi</label>
                                                                <select class="form-control" v-model="params.pt">
                                                                    <option v-for="(item, i) in pts" :key="i" v-text="'Bài thi '+item.title +' '+ item.date.format('DD/MM/YYYY')" v-bind:value="i"></option>
                                                                    {{-- <option>Bài thi JLPT N2 21/06/2020</option>
                                                                    <option>Bài thi JLPT N2 15/06/2020</option>
                                                                    <option>Bài thi JLPT N2 7/06/2020</option> --}}
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="button" @click="getRanks()" class="btn">Lọc kết quả</button>
                                        </div>
                                    </form>

                                    <div class="exam-alert" v-if="!notInList">
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
                                                <tr v-for="(item, i) in ranks" :key="i" v-bind:class="getPrizeClass(i)">
                                                    <td>
                                                        <span class="f-top"  v-if="i <= 2" >
                                                            <img v-if="i == 0" src="{{asset('tomato/assets/img/icon/top1.png')}}" />
                                                            <img v-if="i == 1" src="{{asset('tomato/assets/img/icon/top2.png')}}" />
                                                            <img v-if="i == 2" src="{{asset('tomato/assets/img/icon/top3.png')}}" />
                                                        </span>
                                                        <span class="f-top" v-if="i > 2" v-text="i+1">
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="f-name">
                                                            <span class="f-name__avatar" v-bind:style="{backgroundImage: 'url(' + item.user.avatar +')'}"></span>
                                                            <h4 class="f-name__name" v-text="item.user.name"></h4>
                                                        </div>
                                                    </td>
                                                    <td><a href="javascript:;" class="f-btn" @click="openDetails(i)">Xem kết quả</a></td>
                                                    <td v-text="item.score"></td>
                                                    <td v-text="item['practice_test']['level']['title']"></td>
                                                    <td><span v class="f-icon"><i class="fa" v-bind:class="[item.is_pass?'fa-check':'fa-close']"></i></span>
                                                    </td>
                                                    <td>
                                                        <span v-if="i>2">0</span>
                                                        <span v-if="i<3" class="f-reward" tabindex="0" data-toggle="tooltip"
                                                            data-placement="left"
                                                            title="Liên hệ với bên quản lý để quy đổi sang thời gian sử dụng khoá học" v-text="'+ '+getPrize(i)"></span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div v-if="openInfo" class="modal fade" id="diploma-popup-temp" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="diploma" v-if="openInfo['practice_test']['level']['parent']['system_key']==='pt_japan'">
                                                    <div class="diploma__inner">
                                                        <div class="diploma__header">
                                                            <h2 class="f-title">日本語 能力試験　合否結果通知書</h2>
                                                            <h3 class="f-title-en">Japanese Language Proficiency Test</h3>
                                                        </div>
                                                        <div class="diploma__content">
                                                            <ul class="diploma__list">
                                                                <li>受験日: <b v-text="toDate(openInfo.test_date)"></b></li>
                                                                <li>受験レベル Level: <b v-text="openInfo['practice_test']['level']['title']"></b></li>
                                                                <li>氏名 Name: <b v-text="openInfo['user']['name']"></b></li>
                                                            </ul>
                                                            
                                                            <div class="diploma__tablewrap">
                                                                <table class="diploma__table">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="f-left">
                                                                                <div class="f-left__header">
                                                                                    <p>得点区分別得点</p>
                                                                                    <p>Scores by Scoring Section</p>
                                                                                </div>
                                                                                <div class="f-left__title">
                                                                                    <span class="item" v-for="(item, i) in Object.keys(openInfo['sections'])" :key="i" v-text="openInfo['sections'][item]['session']['name']"></span>
                                                                                    {{-- <span class="item">Từ vựng (聴解)</span>
                                                                                    <span class="item">聴解</span> --}}
                                                                                </div>
                                                                            </td>
                                                                            <td class="f-right">
                                                                                <p>総合得点</p> <p>Total Score</p>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="f-left result">
                                                                                <div class="f-left__title">
                                                                                    <span class="item" v-for="(item, i) in Object.keys(openInfo['sections'])" :key="i"  v-text="getSectionScore(item)"></span>
                                                                                   
                                                                                </div>
                                                                            </td>
                                                                            <td class="f-right result">
                                                                                <span v-text="openInfo['score']"></span> /  <span v-text="openInfo['max_score']"></span>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                    
                                                                <div class="diploma__footer">
                                                                    <span class="f-pass-btn"><span v-if="openInfo['is_pass']">合  格  Passed</span></span>
                                    
                                                                    <a href="#" class="f-logo"><img src="{{ asset('tomato/assets/img/logo.png') }}"></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn-close" data-dismiss="modal"><i class="pe-icon-close"></i></button>
                                                </div>
                                            </div>
                                        </div>
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
                                                        <select class="form-control" id="year" name="year">
                                                            @for ($i = 1; $i <= 12; $i++)
                                                                <option @if($month == $i) selected @endif value="{{ $i }}">Tháng {{ $i }}</option>
                                                            @endfor
                                                            {{-- <option>Tháng 1</option>
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
                                                            <option>Tháng 12</option> --}}
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
                                    @if(count($histories)<=0)
                                    <div class="exam-alert">
                                        <p>Không có lịch sử thi ( trường hợp không có sẽ hiện box này )</p>
                                    </div>
                                    @endif 
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
                                                <?php $index = 1; ?>
                                                @foreach ($histories as $key => $h)
                                                <tr>
                                                    <td>{{$index}}</td>
                                                    <td class="f-name">{{$h->practiceTest->title}} <b>{{$h->practiceTest->level->title}}</b></td>
                                                    <td>{{date('d/m/Y', strtotime($h->test_date))}}</td>
                                                    <td>{{$h->score}}</td>
                                                    
                                                    <td>@if($h->top > 0 && $h->is_pass)<span class="f-top">
                                                        @switch($h->top)
                                                        @case(1)
                                                        <img src="{{asset('tomato/assets/img/icon/top1.png')}}">
                                                        @break
                                                        @case(2)
                                                        <img src="{{asset('tomato/assets/img/icon/top2.png')}}">
                                                        @break
                                                        @case(3)
                                                        <img src="{{asset('tomato/assets/img/icon/top3.png')}}">
                                                        @break
                                                        @default
                                                        {{$top}}
                                                        @endswitch
                                                        </span>@else Không @endif</td>
                                                    <td><span class="f-icon"><i class="fa @if($h->is_pass) fa-check @else fa-close @endif"></i></span>
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
                                                <?php $index++; ?>
                                                @endforeach
                                                {{-- <tr>
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
                                                </tr> --}}
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
@if(isset($ranks))
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
    @endif
@endsection

@section('script')
    <script>
    @if(isset($ranks))
    new Vue({
        el: '#rank-content',
            data: {
               levels:[],
               params: {levelId: null, year: moment().year(), month: moment().month()+1, pt: 0, languageId: "{{ isset($defaultLanguage) ? $defaultLanguage : 'undefined' }}"},
               pts:[],
               years:[],
               originalPts:[],
               ranks:[],
               notInList: false,
               openInfo: null,
            },
            mounted() {
                console.log('acascc')
                let self = this;
                self.years = self.getYears(2019).reverse();
                this.changeLanguage().then((x)=>{
                    self.getRanks();
                })
            },
            methods: {
                changeLanguage: function(){
                    let self = this;
                    return new Promise((resolve, reject)=>{
                        self.getLevel(self.params.languageId)
                        .then((e)=>{
                    self.getPracticeTests(self.params.levelId).then((x)=>{
                        resolve(x);
                    })
                });    
            })
        },
                getMonthDateRange: function(year, month) {
                    var startDate = moment([year, month - 1]);
                    var endDate = moment(startDate).endOf('month');
                    return { start: startDate, end: endDate };
                },
                openDetails: function(i){
                    let self = this;
                    let rank = this.ranks[i];
                    $('#diploma-popup').remove()
                    if(rank){
                        let parentId= rank['practice_test']['level']['parent_id'];
                        if(parentId){
                            self.getSection(parentId).then((response)=>{
                                rank['sections'] = response['list']
                                self.openInfo = rank;
                                setTimeout(function() {
                                    var modal = $('#diploma-popup-temp').clone().attr('id', 'diploma-popup');
                                modal.appendTo('body');
                                $('#diploma-popup').modal('show')}, 100)
                              
                            })
                        }
                    }
                },
                getYears: function(startYear) {
                    var currentYear = new Date().getFullYear(), years = [];
                    startYear = startYear || 1980;
                    while ( startYear <= currentYear ) {
                        years.push(startYear++);
                    }   
                    return years;
                },
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
                reset:function(){
                    this.pts = [];
                    this.originalPts = []
                },
                getLevel:function(id){
                    let self = this;
                    return new Promise((resolve, reject) =>{
                        this.getLevelBase(id).then(function(response){
                        self.levels = response.levels;
                        if(self.levels[0]){
                            self.params.levelId = self.levels[0]['id']
                        }else{
                            self.params.levelId = null
                        }
                        resolve(response)
                    }).catch(e => {
                        reject(e)
                        self.reset()
                    })
                    })
                }, 
                getLevelBase:function(id){
                    return axios.get('{{ route('practice_test.rank.level') }}', {params: {id: id}})
                },
            changeInput:function(){
                
            },

            onChangeTime:function(event){
                this.pts = this.changeTime(this.originalPts).sort((a,b) => moment(a).valueOf() - moment(b).valueOf()).reverse();
            },
            changeType: function(event){
                let self = this;
                this.getPracticeTests(this.params.levelId)
            },
            changeTime: function(arr){
                let self = this;
                let result =[];
                arr.forEach(function(s){
                            let dd = self.getMonthDateRange(self.params.year, self.params.month);
                            let dates = self.getDates(dd.start, dd.end)
                            if(s.loop){
                                let tempD = dates.filter((d)=> s.loop_days.includes(moment(d).isoWeekday(d.day()).day()))
                                tempD.forEach(function(dd){
                                    result.push({'title': s.title, id: s.id, date: dd})
                                })
                            }
                        })
                        return result;
            },
             getSection:function(id){
                return axios.get('{{ route('practice_test.getSections') }}', {params: {id: id}});
             },
            getPracticeTests: function(id){
                    let self = this;
                    return new Promise((resolve, reject) =>{
                        axios.get('{{ route('practice_test.rank.pt') }}', {params: {id: id}}).then((response)=>{
                            let result = self.changeTime(response.pts);
                            self.originalPts = response.pts;
                            self.pts = result.sort((a,b) => moment(a).valueOf() - moment(b).valueOf()).reverse();
                            resolve(response);
                    }).catch(e => {
                        console.log('Fail')
                        self.reset()
                        reject(e);
                    });
                })
            },
            getPrize: function(i){
                switch (i){
                    case 0: return "1 tháng";
                    case 1: return "15 ngày";
                    case 2: return "10 ngày";
                }
                return 0;
            },
            getPrizeClass: function(i){
                if(i>2 && i<11){
                    return "top-4-10"
                }
                switch (i){
                    case 0: return "top-1";
                    case 1: return "top-2";
                    case 2: return "top-3";
                }
                return "";
            },
            toDate: function(d){
                return moment(d).format('YYYY年 MM月 DD日');
            },
            getSectionScore: function(key) {
                    let r = _.find(this.openInfo['section_results'], function(o) {
                        return o.practice_test_session_id == key;
                    });
                    if (!r) {
                        return "0/0";
                    }
                    return r['score'] + " / " + r['max_score'];
                },
            getRanks: function(){
                let self = this;
                let item = self.pts[self.params.pt];
                if(!item){
                    self.ranks = []
                    return
                }
                let id = item.id;
                let date = item.date.format('DD-MM-YYYY');
                    return new Promise((resolve, reject) =>{
                        axios.get('{{ route('practice_test.rank.listRanks') }}', {params: {id: id, date: date}}).then((response)=>{
                            self.ranks = response.list;
                            self.notInList = response.us;
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