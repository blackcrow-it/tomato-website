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

                        <template v-if="question.type == 'multiple-correct-word-position'">
                            <div>
                                <div style="padding-bottom: 10px">
                                    <draggable class="p-1 group-test-draggable" :group="'q-sentence-multiple-' + questionIndex" @add="question.selectedIndex = undefined">
                                        <span v-for="(option, optionIndex) in question.options">@{{ option.correct }}</span>
                                    </draggable>
                                </div>
                                <div v-for="(option, optionIndex) in question.options" class="test-draggable group-result-draggable">
                                    @{{ optionIndex + 1 }}. <span>@{{ option.start }}</span>
                                    <draggable
                                        style="border-bottom: 1px solid black"
                                        class="px-2 item-multiple-word"
                                        :class="{ 'right': submited && option.correct == $('#result-multiple-word-q' + questionIndex + '-' + optionIndex).text(), 'wrong': submited && option.correct != $('#result-multiple-word-q' + questionIndex + '-' + optionIndex).text() }"
                                        :id="'result-multiple-word-q' + questionIndex + '-' + optionIndex"
                                        :group="'q-sentence-multiple-' + questionIndex"
                                    ></draggable>
                                    <span>@{{ option.end }}</span>
                                </div>
                                <div v-if="submited" class="">
                                    <p>Đáp án:</p>
                                    <p v-for="(option, optionIndex) in question.options">
                                        @{{ optionIndex + 1 }}. <span>@{{ question.options[optionIndex]['start'] }}</span> <span style="text-decoration: underline">@{{ question.options[optionIndex]['correct'] }}</span> <span>@{{ question.options[optionIndex]['end'] }}</span>
                                    </p>
                                </div>
                            </div>
                        </template>

                        <template v-if="question.type == 'translate-text'">
                            <div>
                                Nội dung trả lời:
                                <textarea type="input" v-model="answer[questionIndex]" @blur="trimSpaceAnwer(questionIndex)" class="form-control"></textarea>
                                <label v-for="(option, optionIndex) in question.options" class="choose-label" :class="{ 'true': submited && compareTextOptionWithAnswer(option, questionIndex) }">
                                </label>
                            </div>
                            <div v-if="submited" style="margin-top:5px">
                                <label>Các câu trả lời đúng:</label>
                                <ul class="test-draggable" v-for="(option, optionIndex) in question.options" class="px-1" :class="{ 'test-draggable-true': compareTextOptionWithAnswer(option, questionIndex) }">
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
                        <li>Tổng điểm: <b>@{{ totalPointCorrectAnswer() + '/' + 10 }}</b></li>
                        <li>
                            Kết quả:
                            <b v-if="isNotPassTheTest()">Chưa đạt</b>
                            <b v-else>Đạt</b>
                        </li>
                    </ul>
                    <div class="quiz-reslut__btn text-left">
                        <button type="button" class="btn" @click="scrollToQuiz">Xem đáp án</button>
                        <button type="button" class="btn" @click="skipTest">Bài học tiếp theo</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($test_result)
    <div class="quiz-history">
        <h3>Kết quả bài làm trước đó</h3>
        <div class="table-historyExam table-responsive">
            <table>
                <thead>
                    <tr><th>STT</th>
                    <th>Thời gian</th>
                    <th>Điểm</th>
                    <th>Đạt</th>
                </tr></thead>
                <tbody>
                    @foreach ($test_result as $key => $value)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $value->created_at }}</td>
                            <td>{{ $value->score }}</td>
                            <td>
                                @if($value->is_pass)
                                <span class="f-icon"><i class="fa fa-check"></i></span></td>
                                @else
                                <span class="f-icon"><i class="fa fa-close"></i></span>
                                @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection

@section('part_script')
<style>
    .group-test-draggable span {
        background: #5e8bab;
        padding: 3px 7px;
        margin: 2px 5px;
        border-radius: 5px;
        color: #fff;
        cursor: move;
        display: inline-block;
    }
    .group-result-draggable .px-2 span {
        cursor: move;
        padding-right: 2.5px;
        padding-left: 2.5px;
    }
    .group-result-draggable .item-multiple-word.right {
        color: #77af41;
    }
    .group-result-draggable .item-multiple-word.wrong {
        color: #e71d36;
    }
    .quiz-history {
        padding-top: 50px;
    }
    @media only screen and (min-width: 1024px) {
        .quiz-history {
            width: 50%;
            margin: auto;
        }
    }
</style>
<script>
    new Vue({
        el: '#test',
        data: {
            questions: [],
            submited: false,
            correct_requirement: 0,
            answer: {},
        },
        async mounted() {
            this.questions = await axios.get("{{ route('part_test.get_data', [ 'id' => $part->id ]) }}");
            this.questions = this.questions.map(question => {
                if (question.type != 'multiple-choice') return question;
                if (question.options != undefined && question.options.length >= 1) {
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
                }
                return question;
            });
            this.questions.forEach((question, questionIndex) => {
                question.options.forEach((option, optionIndex) => {
                    if (question.type == 'translate-text') {
                            this.answer[questionIndex] = '';
                    } else if (question.type == 'multiple-correct-word-position') {
                        question.options.forEach((option, optionIndex) => {
                            option['selected'] = []
                        });
                    }
                });
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

                // console.log(passed)
                axios.post(
                    "{{ route('api.test_result.add') }}",
                    { test_id: {{$part->part_test->id}}, score: this.totalPointCorrectAnswer(), is_pass: passed }
                )
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                })
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
            skipTest() {
                axios.post("{{ route('part.set_complete') }}", { part_id: {{$part->id}} })
                .then(function(response) {
                    window.location.href = "{{route('part', ['id' => $next_part->id]) }}";
                })
                .catch(function(error) {
                    bootbox.alert("<h1>Thông báo</h1>Không thể chuyển đến bài học tiếp theo");
                });
            },
            correctWordPositionSelectedIndex(index) {
                console.log(index);
            },
            isNotPassTheTest() {
                const userCorrectCount = this.questions.filter((question, index) => {
                    if (question.type == 'correct-word-position' || question.type == 'multiple-choice') {
                        return question.selectedIndex == question.correct
                    } else if (question.type == 'correct-word-position-translate') {
                        return this.editText(index) == true
                    } else if (question.type == 'translate-text') {
                        return question.correct == "true"
                    } else if (question.type == 'multiple-correct-word-position') {
                        let result = false;
                        question.options.forEach((option, optionIndex) => {
                            if (option.correct != $('#result-multiple-word-q' + index + '-' + optionIndex).text()) {
                                result = false;
                            } else {
                                result = true;
                            }
                        });
                        return result;
                    }
                    return false
                }).length;
                return userCorrectCount == 0 || userCorrectCount < this.correct_requirement;
            },
            compareTextOptionWithAnswer(option, questionIndex){
                return option.toLowerCase().trim() == this.answer[questionIndex].toLowerCase().trim();
            },
            trimSpaceAnwer(questionIndex){
                this.answer[questionIndex] = this.answer[questionIndex].trim().replace(/\s\s+/g, ' ');
                if(this.questions[questionIndex].options.indexOf(this.answer[questionIndex] ) > -1){
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
                let total = this.questions.filter((question, index) => {
                    if (question.type == 'correct-word-position' || question.type == 'multiple-choice') {
                        return question.selectedIndex == question.correct
                    } else if (question.type == 'correct-word-position-translate') {
                        return this.editText(index) == true
                    } else if (question.type == 'translate-text') {
                        return question.correct == "true"
                    } else if (question.type == 'multiple-correct-word-position') {
                        let result = false;
                        question.options.forEach((option, optionIndex) => {
                            if (option.correct != $('#result-multiple-word-q' + index + '-' + optionIndex).text()) {
                                result = false;
                            } else {
                                result = true;
                            }
                        });
                        return result;
                    }
                    return false
                });
                return  total.length  + '/' + this.questions.length;
            },
            totalPointCorrectAnswer(){
                let total = this.questions.filter((question, index) => {
                    if (question.type == 'correct-word-position' || question.type == 'multiple-choice') {
                        return question.selectedIndex == question.correct
                    } else if (question.type == 'correct-word-position-translate') {
                        return this.editText(index) == true
                    } else if (question.type == 'translate-text') {
                        return question.correct == "true"
                    } else if (question.type == 'multiple-correct-word-position') {
                        let result = false;
                        question.options.forEach((option, optionIndex) => {
                            if (option.correct != $('#result-multiple-word-q' + index + '-' + optionIndex).text()) {
                                result = false;
                            } else {
                                result = true;
                            }
                        });
                        return result;
                    }
                    return false
                });
                return (Math.round(total.length / this.questions.length * 100) / 10 );
            }
        },

    });

</script>
@endsection
