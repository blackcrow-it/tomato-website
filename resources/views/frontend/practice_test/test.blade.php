@extends('frontend.master')
@section('body')
    <section class="section page-title">
        <div class="container">
            <nav class="breadcrumb-nav">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home.html">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="baithi.html">Bài thi</a></li>
                </ol>
            </nav>
            <h1 class="page-title__title">Bài thi {{$language->title}} trình độ {{$pt->level->title}}</h1>
        </div>
    </section>

    <section class="section section-quiz-detail" id="section-quiz-detail" v-bind:class="{'pointerEventsNone': stopTime}">
        <div class="container">
            <div class="layout layout--right">
                <div class="row stickyJs fix-header-top">
                    <div class="col-xl-9">
                        <div class="layout-content">
                            <div class="quiz-wrap show-start">
                                <div class="quiz-wrap__start" v-if="showStart">
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
                                        <a href="javascript:;" @click="start()" class="btn">Bắt đầu thi</a>
                                    </div>
                                </div>
                                <div class="quiz-wrap__inner" v-if="!showStart">
                                    <div class="quiz-wrap__header">
                                        <div class="header-left">
                                            <p>Họ và tên <span>{{ auth()->user()->name ?? auth()->user()->username }}</span></p>
                                        </div>
                                        <div class="header-right">
                                            <h3 class="header-subtitle">Trung tâm ngoại ngữ Tomato</h3>
                                            <h2 class="header-title">Bài thi thử {{$language->title}}</h2>
                                            <span class="header-time">Thời gian làm bài: <b>{{ $pt->duration }}
                                                    phút</b></span>
                                        </div>
                                    </div>
                                    <div class="quiz-wrap__content">
                                        <div class="tab-content" id="myTabContent">
                                            <div v-for="(s, i) in Object.keys(sessions)" :key="i" v-bind:id="'panel-'+ s"
                                                role="tabpanel" v-bind:aria-labelledby="'tab-'+s" class="tab-pane fade"
                                                v-bind:class="{'active show': i == 0}">
                                                <h2 class="tab-pane__title">Phần <span v-text="i+1"></span>: <span
                                                        v-text="sessionTypes[s]['name']"></span> (<span
                                                        v-text="sessions[s].length"></span> câu)</h2>
                                                <ul class="quiz__list">
                                                    <li class="item" v-for="(q, j) in sessions[s]" :key="j">
                                                        <div class="item__title">
                                                            <p><b>Câu hỏi <span v-text="j+1"></span>: </b><span
                                                                    v-text="q.content"></span></p>
                                                            {{-- <div class="item__control">
                                                                    <audio controls>
                                                                        <source src="assets/audio/1-1-1.mp3" type="audio/mpeg">
                                                                    </audio>
                                                                </div> --}}
                                                        </div>
                                                        <div class="item__choose">
                                                            <label class="choose-label" v-for="(a, ai) in q.answers"
                                                                :key="ai">
                                                                <input v-model="q.answered" type="radio" name="quiz-1"
                                                                    v-bind:value="a.id">
                                                                <div class="choose-label__inner">
                                                                    <span class="choose-label__check"
                                                                        v-text="String.fromCharCode(65+ai)"></span>
                                                                    <p v-text="a.content"></p>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="quiz-wrap__footer">
                                            <!-- btn-diploma -->
                                            <a href="javascript:;" class="btn btn-showResult" @click="done()">Nộp bài</a>
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
                                                                <p>総合得点</p>
                                                                <p>Total Score</p>
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
                                                    <span class="f-pass-btn">合 格 Passed</span>

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
                            <div class="widget widget--infoQuiz" v-bind:class="{'pointerEventsNone': showStart}">
                                <h2 class="widget__title">Thông tin</h2>

                                <div class="infoQuiz__wrap">
                                    <div class="infoQuiz-content timer-inner">
                                        <div class="timeCounterJs " data-time="1">
                                            <ul>
                                                <li class="minutes"><span v-text="parseInt(countDown/60)"></span>phút</li>
                                                <li class="seconds"><span v-text="parseInt(countDown%60)"></span>giây</li>
                                            </ul>
                                            <p class="notify" v-text="notiText"></p>
                                        </div>
                                    </div>

                                    <div class="infoQuiz__nav infoQuiz-content">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <?php $in = 0; ?>
                                            @foreach ($questions as $key => $question)
                                                <li class="nav-item">
                                                    <a class="nav-link @if ($in == 0) active @endif"
                                                        id="tab-{{ $key }}" data-toggle="tab"
                                                        href="#panel-{{ $key }}" role="tab"
                                                        aria-controls="panel-{{ $key }}" aria-selected="true">Phần
                                                        {{ $in + 1 }}: {{ $sessions[$key]->name }}<span>(Đã làm: <span
                                                                v-text="getAnswered({{ $key }})"></span>/{{ count($questions[$key]) }})
                                                            <i v-if="getAnswered({{ $key }}) == {{ count($questions[$key]) }}" class="fa fa-check"></i></span></a>
                                                </li>
                                                <?php $in++; ?>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div class="infoQuiz-content infoQuiz__user">
                                        <p>Số người tham gia: <b><i class="fa fa-user"></i>{{count($users)}}</b></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="alertbox-popup" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<p class="f-text">Bạn còn nhiều thời gian!<br>Bạn chắc chắn muốn nộp bài</p>
				<div class="f-btn">
					<a href="javascript:;" class="btn btn-agree btn--sm btn--secondary">Đồng ý</i></a>
					<a href="javascript:;" class="btn btn--sm" data-dismiss="modal" >Huỷ bỏ</i></a>
				</div>
			</div>
		</div>
	</div>
@endsection
    
@section('script')
    <script>
        new Vue({
            el: '#section-quiz-detail',
            data: {
                sessions: {!! json_encode($questions, JSON_HEX_TAG) !!},
                sessionTypes: {!! json_encode($sessions, JSON_HEX_TAG) !!},
                pt: {!! json_encode($pt, JSON_HEX_TAG) !!},
                showStart: true,
                countDown: 0,
                notiText: "",
                stopTime: false,
                timer: null,
            },
            mounted() {
                var self = this;
                this.countDown = this.pt.duration * 60;
                $('#alertbox-popup .btn-agree').on('click', function (e) {
                    e.preventDefault();
                    clearTimeout(self.timer);
                    self.submitTest();
                    self.stopTime = true;
                    $('#alertbox-popup').modal('hide');
                })
            },
            methods: {
                start: function() {
                    axios.get('{{ route('practice_test.startTest')}}', {params:{
                        pt_id: this.pt.id
                    }}).then(response => {
                        console.log('start Success')
                        this.showStart = false;
                        this.notiText = "Bắt đầu làm bài"
                        this.checkTime();
                    }).catch(e => {
                        console.log('start Fail')
                    });
                },
                done: function() {
                    $('#alertbox-popup').modal('show');
                },
                getAnswered: function(id) {
                    return this.sessions[id].filter(x => x.answered != null).length;
                },
                checkTime: function() {
                    if (this.stopTime) {
                        return
                    }
                    if (this.countDown < 180) {
                        this.notiText = "Sắp hết thời gian"
                    }
                    if (this.countDown <= 0) {
                        this.stopTime = true;
                        clearTimeout(this.timer);
                        this.notiText = 'Đã nộp bài';
                        this.submitTest();

                    } else {
                        this.countDown--;
                        this.timer = setTimeout(this.checkTime, 1000);
                    }
                },
                submitTest: function(){
                    let self = this;
                    let formData = new FormData();
                    let answers = [];
                    Object.keys(this.sessions).forEach(function(k){
                        let temp = self.sessions[k].map(x=> {
                        let obj = {'questionId': x.id, 'answer': x['answered']??null}
                        return obj;
                    })
                    answers = [...answers, ...temp];
                    })
                    
                    formData.append('data', JSON.stringify(answers))
                    axios.post('{{ route('practice_test.submitTest')}}',
                        formData
                    ).then(response => {
                        console.log('Submit Success')
                    }).catch(e => {
                        console.log('Submit Fail')
                    });
                }
            },
        })
    </script>
@endsection
