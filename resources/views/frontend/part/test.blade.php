@extends('frontend.part.master')

@section('content')
<div class="learningLesson__quiz" id="test">
    <div class="quiz-wrap">
        <div class="quiz-wrap__header">
            <div class="header-left">
                <p>Họ và tên <span>{{ auth()->user()->name ?? auth()->user()->username }}</span></p>
            </div>
            <div class="header-right">
                <h3 class="header-subtitle">Trung tâm ngoại ngữ Tomato</h3>
                <h2 class="header-title">Kiểm tra hiểu bài</h2>
            </div>
        </div>
        <div class="quiz-wrap__inner" :class="{ 'pointerEventsNone': submited }">
            <div class="quiz-wrap__content">
                <ul class="quiz__list">
                    <li v-for="(question, questionIndex) in questions" class="item">
                        <div class="item__title">
                            <p><b>Câu hỏi số @{{ questionIndex + 1 }}:</b> @{{ question.question }}</p>
                            <div class="item__control">
                                <audio v-if="question.audio" :src="question.audio" controls controlsList="nodownload"></audio>
                            </div>
                        </div>
                        <div class="item__choose">
                            <label v-for="(option, optionIndex) in question.options" class="choose-label" :class="{ 'true': submited && question.correct == optionIndex }">
                                <input type="radio" v-model="question.selectedIndex" :value="optionIndex">
                                <div class="choose-label__inner">
                                    <span class="choose-label__check">@{{ String.fromCharCode(65 + optionIndex) }}</span>
                                    <p>@{{ option.value }}</p>
                                </div>
                            </label>
                        </div>
                    </li>
                </ul>

                <div class="quiz-wrap__footer">
                    <button type="button" class="btn" @click="submit" :class="{ 'd-none': submited }">Nộp bài</button>
                </div>
            </div>
        </div>
    </div>

    <div class="quiz-reslut">
        <div class="quiz-reslut__inner">
            <div class="quiz-reslut__content">
                <div class="quiz-reslut__right">
                    <ul class="quiz-reslut__list">
                        <li>Họ và tên: <b>{{ auth()->user()->name ?? auth()->user()->username }}</b></li>
                        <li>Bài thi: <b>{{ $part->title }}</b></li>
                        <li>Câu hỏi <b>@{{ questions.filter(x => x.selectedIndex == x.correct).length }}/@{{ questions.length }}</b></li>
                        <li>Tổng điểm: <b>@{{ Math.round(questions.filter(x => x.selectedIndex == x.correct).length / questions.length * 100) / 10 }}/10</b></li>
                        <li>
                            Kết quả:
                            <b v-if="questions.filter(x => x.selectedIndex == x.correct).length < correct_requirement">Chưa đạt</b>
                            <b v-else>Đạt</b>
                        </li>
                    </ul>
                    <div class="quiz-reslut__btn text-left">
                        <button type="button" class="btn" @click="scrollToQuiz">Xem đáp án</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('part_script')
<script>
    new Vue({
        el: '#test',
        data: {
            questions: [],
            submited: false,
            correct_requirement: 0,
        },
        mounted() {
            this.questions = JSON.parse(`{!! json_encode($data->data) !!}`);
            this.questions = this.questions.map(question => {
                question.options = question.options.map(opt => {
                    return {
                        value: opt,
                        is_correct: false
                    };
                });
                question.options[question.correct].is_correct = true;
                question.options = this.shuffle(question.options);
                question.correct = question.options.findIndex(opt => opt.is_correct);
                return question;
            });
            this.questions = this.shuffle(this.questions);

            this.correct_requirement = parseInt('{{ $data->correct_requirement ?? 0 }}');
        },
        methods: {
            shuffle(arr) {
                return parseInt('{{ ($data->random_enabled ?? false) ? 1 : 0 }}') ? arr.sort(() => Math.random() - 0.5) : arr;
            },
            submit() {
                this.submited = true;

                $('.quiz-reslut').slideDown();
                $('html,body').animate({
                    scrollTop: $('.quiz-reslut').offset().top
                }, 500);
            },
            scrollToQuiz() {
                $('html,body').animate({
                    scrollTop: $('.quiz-wrap').offset().top
                }, 500);
            },
        },
    });

</script>
@endsection
