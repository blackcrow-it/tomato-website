@extends('frontend.part.master')

@section('content')
<div class="learningLesson__quiz" id="test">
    <div class="quiz-wrap">
        <div class="quiz-wrap__header">
            <div class="header-left">
                <p>Họ và tên <span>{{ auth()->user()->name ?? auth()->user()->username }}</span></p>
            </div>
            <div class="header-right">
                <div class="header-subtitle">Trung tâm ngoại ngữ Tomato</div>
                <div class="header-title">Kiểm tra hiểu bài</div>
            </div>
        </div>
        <div class="quiz-wrap__inner" :class="{ 'pointerEventsNone': submited }">
            <div class="quiz-wrap__content">
                <ul class="quiz__list">
                    <li v-for="(question, questionIndex) in questions" class="item">
                        <div class="item__title">
                            <div class="font-weight-bold">Câu hỏi số @{{ questionIndex + 1 }}:</div>
                            <div v-html="question.question"></div>
                            <div class="item__control">
                                <audio v-if="question.audio" :src="question.audio" controls controlsList="nodownload"></audio>
                            </div>
                        </div>
                        <div v-if="question.type == 'multiple-choice'" class="item__choose">
                            <label v-for="(option, optionIndex) in question.options" class="choose-label" :class="{ 'true': submited && question.correct == optionIndex }">
                                <input type="radio" v-model="question.selectedIndex" :value="optionIndex">
                                <div class="choose-label__inner">
                                    <span class="choose-label__check">@{{ String.fromCharCode(65 + optionIndex) }}</span>
                                    <p>@{{ option.value }}</p>
                                </div>
                            </label>
                        </div>
                        <template v-if="question.type == 'correct-word-position'">
                            <div class="test-draggable">
                                <draggable class="px-2" :group="'q-sentence-' + questionIndex" @add="question.selectedIndex = 0" @update="question.selectedIndex = 0"></draggable>
                                <template v-for="(option, optionIndex) in question.options" v-if="optionIndex != question.correct">
                                    <span class="px-1">@{{ option }}</span>
                                    <draggable class="px-2" :group="'q-sentence-' + questionIndex" @add="question.selectedIndex = optionIndex + 1" @update="question.selectedIndex = optionIndex + 1"></draggable>
                                </template>
                            </div>
                            <label>Từ còn thiếu (kéo thả vào vị trí thích hợp trong câu trên)</label>
                            <div class="test-draggable">
                                <draggable class="p-1" :group="'q-sentence-' + questionIndex" @add="question.selectedIndex = undefined">
                                    <span v-for="(option, optionIndex) in question.options" v-if="optionIndex == question.correct" class="test-draggable-missing-word" :class="{ 'true': submited && question.selectedIndex == question.correct, 'wrong': submited && question.selectedIndex != question.correct }">@{{ option }}</span>
                                </draggable>
                            </div>
                            <div v-if="submited" class="">
                                <label>Đáp án</label>
                                <div class="test-draggable">
                                    <span v-for="(option, optionIndex) in question.options" class="px-1" :class="{ 'test-draggable-true': optionIndex == question.correct }">@{{ option }} </span>
                                </div>
                            </div>
                        </template>

                        <template v-if="question.type == 'translate-text'">
                            <div>
                                Nội dung trả lời: <input type="input" v-model="answer" @blur="trimSpaceAnwer(questionIndex)" class="form-control">
                                <label v-for="(option, optionIndex) in question.options" class="choose-label" :class="{ 'true': submited && compareTextOptionWithAnswer(option) }">
                                </label>
                            </div>
                            <div v-if="submited" style="margin-top:5px">
                                <label>Các câu trả lời đúng:</label>
                                <ul class="test-draggable" v-for="(option, optionIndex) in question.options" class="px-1" :class="{ 'test-draggable-true': compareTextOptionWithAnswer(option) }">
                                    <li style="list-style-type: none">@{{optionIndex + 1 + '.' + option }} </li>
                                </ul>
                            </div>
                        </template>

                        <template v-if="question.type == 'correct-word-position-translate'">
                            <div class="test-draggable">
                                <div style="display: flex">
                                    <draggable class="px-2" :group="'q-sentence-' + questionIndex" @add="question.selectedIndex = 0" @update="question.selectedIndex = 0"></draggable>
                                    <template v-for="(option, optionIndex) in question.options">
                                        <div>
                                            <span class="px-2" style="text-decoration: underline" :id="'word-position-' + optionIndex" @blur="editText(questionIndex,optionIndex)" contenteditable="true">@{{ option }}</span>
                                            <div class="px-2 font-weight-bold" style="text-align: center" :class="{ 'test-draggable-true': submited && question.correct == optionIndex }">@{{ String.fromCharCode(65 + optionIndex) }}</div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div v-if="submited" class="">
                                <label>Đáp án</label>
                                <div class="test-draggable">
                                    <span class="px-1 font-weight-bold">@{{ String.fromCharCode(65 + parseInt(question.correct)) }}. @{{ question.textCorrect }}</span>
                                </div>
                            </div>
                        </template>
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
                        <li>Câu hỏi <b>@{{ totalCorrectAnswer() }}</b></li>
                        <li>Tổng điểm: <b>@{{ totalPointCorrectAnswer() }}</b></li>
                        <li>
                            Kết quả:
                            <b v-if="isNotPassTheTest()">Chưa đạt</b>
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
            answer:'',
        },
        async mounted() {
            this.questions = await axios.get("{{ route('part_test.get_data', [ 'id' => $part->id ]) }}");
            this.questions = this.questions.map(question => {
                if (question.type != 'multiple-choice') return question;

                question.options = question.options.map(opt => {
                    return {
                        value: opt,
                        is_correct: false
                    };
                });
                question.options[question.correct].is_correct = true;

                if (!parseInt('{{ ($data->random_enabled ?? false) ? 1 : 0 }}')) return question;

                question.options = this.shuffle(question.options);
                question.correct = question.options.findIndex(opt => opt.is_correct);
                return question;
            });
            if (parseInt('{{ ($data->random_enabled ?? false) ? 1 : 0 }}')) {
                this.questions = this.shuffle(this.questions);
            }

            this.correct_requirement = parseInt('{{ $data->correct_requirement ?? 0 }}');
        },
        methods: {
            shuffle(arr) {
                return arr.sort(() => Math.random() - 0.5);
            },
             submit() {
                 this.submited = true;

                 $('.quiz-reslut').slideDown();
                 $('html,body').animate({
                    scrollTop: $('.quiz-reslut').offset().top
                }, 500);
                const passed = !this.isNotPassTheTest();
                console.log(passed)
                if (passed) {
                    axios.post("{{ route('part.set_complete') }}", { part_id: {{$part->id}} })
                    .then(function (response) {
                        console.log(response);
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                }
            },
            scrollToQuiz() {
                $('html,body').animate({
                    scrollTop: $('.quiz-wrap').offset().top
                }, 500);
            },
            correctWordPositionSelectedIndex(index) {
                console.log(index);
            },
            isNotPassTheTest() {
                const userCorrectCount = this.questions.filter(x => x.selectedIndex == x.correct || x.correct == true).length;
                return userCorrectCount == 0 || userCorrectCount < this.correct_requirement;
            },
            compareTextOptionWithAnswer(option){
                return option.toLowerCase().trim() == this.answer.toLowerCase().trim();
            },
            trimSpaceAnwer(questionIndex){
                this.answer = this.answer.trim().replace(/\s\s+/g, ' ');
                if(this.questions[questionIndex].options.indexOf(this.answer ) > -1){
                    this.questions[questionIndex].correct = "true";
                }
            },
            editText(questionIndex) {
                let countFalse = 0;
                for (let i = 0; i < this.questions[questionIndex].options.length; i++) {
                    if (i == this.questions[questionIndex].correct) {
                        continue
                    }

                    if (($('#word-position-' + i).text()) !== this.questions[questionIndex].options[i]) {
                        countFalse++
                    }
                }

                if (countFalse) {
                    return false
                }

                let correctIndex = parseInt(this.questions[questionIndex].correct)

               return $('#word-position-' + correctIndex).text() == this.questions[questionIndex].textCorrect;
            },
            totalCorrectAnswer(){
                let total = this.questions.filter((question, index) => question.selectedIndex == question.correct
                    || question.correct == "true" || this.editText(index) == true).length;
                return  total  + '/' + this.questions.length;
            },
            totalPointCorrectAnswer(){
                let total = this.questions.filter((question, index) => question.selectedIndex == question.correct
                    || question.correct == "true" || this.editText(index) == true).length;
                return (Math.round(total / this.questions.length * 100) / 10 ) + '/' + 10;
            }
        },

    });

</script>
@endsection
