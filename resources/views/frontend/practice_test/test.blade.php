@extends('frontend.master')
@section('body')
<div class="page-content">
    <section class="section page-title">
        <div class="container">
            <nav class="breadcrumb-nav">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home.html">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="baithi.html">Bài thi</a></li>
                </ol>
            </nav>
            <h1 class="page-title__title">Bài thi tiêng Trung trình độ N2</h1>
        </div>
    </section>

    <section class="section section-quiz-detail">
        <div class="container">
            <div class="layout layout--right">
                <div class="row stickyJs fix-header-top">
                    <div class="col-xl-9">
                        <div class="layout-content">
                            <div class="quiz-wrap show-start">
                                <div class="quiz-wrap__start">
                                    <div class="f-content">
                                        <div class="f-text-wrap">
                                            <div class="f-text">
                                                <h4>Phần thưởng:</h4>
                                                <ul>
                                                    <li>Top 1: + 1 tháng khoá học online</li>
                                                    <li>Top 2: + 15 ngày khoá học online</li>
                                                    <li>Top 3: + 10 ngày khoá học online</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <a href="#" class="btn">Bắt đầu thi</a>
                                    </div>
                                </div>
                                <div class="quiz-wrap__inner">
                                    <div class="quiz-wrap__header">
                                        <div class="header-left">
                                            <p>Họ và tên <span>Nguyễn Quốc Khánh</span></p>
                                        </div>
                                        <div class="header-right">
                                            <h3 class="header-subtitle">Trung tâm ngoại ngữ Tomato</h3>
                                            <h2 class="header-title">Bài thi thử tiêng Trung</h2>
                                            <span class="header-time">Thời gian làm bài: <b>{{$pt->duration}} phút</b></span>
                                        </div>
                                    </div>
                                    <div class="quiz-wrap__content">
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                                <h2 class="tab-pane__title">Phần 1: Nghe (20 câu)</h2>
                                                <ul class="quiz__list">
                                                    <li class="item">
                                                        <div class="item__title">
                                                            <p><b>Câu hỏi 1:</b> Nghe câu thoại và trả lời đáp án</p>
                                                            <div class="item__control">
                                                                <audio controls>
                                                                    <source src="assets/audio/1-1-1.mp3" type="audio/mpeg">
                                                                </audio>
                                                            </div>
                                                        </div>
                                                        <div class="item__choose">
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-1">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">A</span>
                                                                    <p>Đáp án 1</p>
                                                                </div>
                                                            </label>
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-1">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">B</span>
                                                                    <p>Đáp án 2</p>
                                                                </div>
                                                            </label>
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-1">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">C</span>
                                                                    <p>Đáp án 3</p>
                                                                </div>
                                                            </label>
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-1">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">D</span>
                                                                    <p>Đáp án 4</p>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="item__title">
                                                            <p><b>Câu hỏi 2:</b> Nghe câu thoại và trả lời đáp án</p>
                                                            <div class="item__control">
                                                                <audio controls>
                                                                    <source src="assets/audio/1-1-1.mp3" type="audio/mpeg">
                                                                </audio>
                                                            </div>
                                                        </div>
                                                        <div class="item__choose">
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-2">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">A</span>
                                                                    <p>Đáp án 1</p>
                                                                </div>
                                                            </label>
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-2">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">B</span>
                                                                    <p>Đáp án 2</p>
                                                                </div>
                                                            </label>
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-2">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">C</span>
                                                                    <p>Đáp án 3</p>
                                                                </div>
                                                            </label>
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-2">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">D</span>
                                                                    <p>Đáp án 4</p>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="item__title">
                                                            <p><b>Câu hỏi 3:</b> Nghe câu thoại và trả lời đáp án</p>
                                                            <div class="item__control">
                                                                <audio controls>
                                                                    <source src="assets/audio/1-1-1.mp3" type="audio/mpeg">
                                                                </audio>
                                                            </div>
                                                        </div>
                                                        <div class="item__choose">
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-3">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">A</span>
                                                                    <p>Đáp án 1</p>
                                                                </div>
                                                            </label>
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-3">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">B</span>
                                                                    <p>Đáp án 2</p>
                                                                </div>
                                                            </label>
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-3">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">C</span>
                                                                    <p>Đáp án 3</p>
                                                                </div>
                                                            </label>
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-3">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">D</span>
                                                                    <p>Đáp án 4</p>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                                <h2 class="tab-pane__title">Phần 2: Từ vựng (20 câu)</h2>
                                                <ul class="quiz__list">
                                                    <li class="item">
                                                        <div class="item__title">
                                                            <p><b>Câu hỏi 1:</b> Nghe câu thoại và trả lời đáp án</p>
                                                            <div class="item__control">
                                                                <audio controls>
                                                                    <source src="assets/audio/1-1-1.mp3" type="audio/mpeg">
                                                                </audio>
                                                            </div>
                                                        </div>
                                                        <div class="item__choose">
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-4">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">A</span>
                                                                    <p>Đáp án 1</p>
                                                                </div>
                                                            </label>
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-4">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">B</span>
                                                                    <p>Đáp án 2</p>
                                                                </div>
                                                            </label>
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-4">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">C</span>
                                                                    <p>Đáp án 3</p>
                                                                </div>
                                                            </label>
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-4">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">D</span>
                                                                    <p>Đáp án 4</p>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="item__title">
                                                            <p><b>Câu hỏi 2:</b> Nghe câu thoại và trả lời đáp án</p>
                                                            <div class="item__control">
                                                                <audio controls>
                                                                    <source src="assets/audio/1-1-1.mp3" type="audio/mpeg">
                                                                </audio>
                                                            </div>
                                                        </div>
                                                        <div class="item__choose">
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-5">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">A</span>
                                                                    <p>Đáp án 1</p>
                                                                </div>
                                                            </label>
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-5">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">B</span>
                                                                    <p>Đáp án 2</p>
                                                                </div>
                                                            </label>
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-5">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">C</span>
                                                                    <p>Đáp án 3</p>
                                                                </div>
                                                            </label>
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-5">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">D</span>
                                                                    <p>Đáp án 4</p>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="item__title">
                                                            <p><b>Câu hỏi 3:</b> Nghe câu thoại và trả lời đáp án</p>
                                                            <div class="item__control">
                                                                <audio controls>
                                                                    <source src="assets/audio/1-1-1.mp3" type="audio/mpeg">
                                                                </audio>
                                                            </div>
                                                        </div>
                                                        <div class="item__choose">
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-6">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">A</span>
                                                                    <p>Đáp án 1</p>
                                                                </div>
                                                            </label>
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-6">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">B</span>
                                                                    <p>Đáp án 2</p>
                                                                </div>
                                                            </label>
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-6">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">C</span>
                                                                    <p>Đáp án 3</p>
                                                                </div>
                                                            </label>
                                                            <label class="choose-label">
                                                                <input type="radio" name="quiz-6">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check">D</span>
                                                                    <p>Đáp án 4</p>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="quiz-wrap__footer">
                                            <!-- btn-diploma -->
                                            <a href="#" class="btn btn-showResult">Nộp bài</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="quiz-reslut">
                                <div class="diploma">
                                    <div class="diploma__inner">
                                        <div class="diploma__header">
                                            <h2 class="f-title">日本語 能力試験　合否結果通知書</h2>
                                            <h3 class="f-title-en">Japanese Language Proficiency Test</h3>
                                        </div>
                                        <div class="diploma__content">
                                            <ul class="diploma__list">
                                                <li>受験日: <b>2020年 6月 13日</b></li>
                                                <li>受験レベル Level: <b>N5</b></li>
                                                <li>氏名 Name: <b>Nguyễn Quốc Khánh</b></li>
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
                                                                    <span class="item">Nghe ( 聴解)</span>
                                                                    <span class="item">Từ vựng (聴解)</span>
                                                                    <span class="item">聴解</span>
                                                                </div>
                                                            </td>
                                                            <td class="f-right">
                                                                <p>総合得点</p> <p>Total Score</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="f-left result">
                                                                <div class="f-left__title">
                                                                    <span class="item">58 / 60</span>
                                                                    <span class="item">59 / 60</span>
                                                                    <span class="item">60 / 60</span>
                                                                </div>
                                                            </td>
                                                            <td class="f-right result">
                                                                177 / 180
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <div class="diploma__footer">
                                                    <span class="f-pass-btn">合  格  Passed</span>

                                                    <a href="#" class="f-logo"><img src="assets/img/logo.png"></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="quiz-reslut__btn">
                                    <a href="#" class="btn btn-view-answer">Xem đáp án</a>
                                    <a href="top-diemcao.html" class="btn btn--secondary">Bảng xếp hạng</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 sticky">
                        <div class="layout-sidebar d-none d-xl-block">
                            <div class="widget widget--infoQuiz pointerEventsNone">
                                <h2 class="widget__title">Thông tin</h2>
                                
                                <div class="infoQuiz__wrap">
                                    <div class="infoQuiz-content timer-inner">
                                        <div class="timeCounterJs " data-time="1">
                                            <ul>
                                                <li class="minutes"><span>1</span>phút</li>
                                                <li class="seconds"><span>00</span>giây</li>
                                            </ul>
                                            <p class="notify"></p>
                                        </div>
                                    </div>
                                    
                                    <div class="infoQuiz__nav infoQuiz-content">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"> Phần 1: Nghe <span>(Đã làm: 20/20) <i class="fa fa-check"></i></span></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Phần 2: Từ vựng <span>( Đã làm: 10/20)</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                    
                                    <div class="infoQuiz-content infoQuiz__user">
                                        <p>Số người tham gia: <b><i class="fa fa-user"></i>200</b></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection