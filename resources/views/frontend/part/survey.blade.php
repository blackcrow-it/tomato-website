@extends('frontend.part.master')

@section('content')
<div class="learningLesson__quiz" id="survey">
    <div class="quiz-wrap">
        <div class="survey-question__header">
            <div class="f-title">{{ $part->title }}</div>
            <div class="f-text">
                <p>{{ $data->description }}</p>
                <p class="text-primary">*Bắt buộc</p>
            </div>
        </div>
        <div class="survey-question__content">
            <div class="survey-question__item">
                <div class="f-label">Họ và tên <span>*</span></div>
                <div class="f-content">
                    <div class="input-item">
                        <div class="input-item__inner">
                            <input type="text" name="name" value="{{ auth()->user()->name }}" placeholder="Họ và tên" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="survey-question__item">
                <div class="f-label">Sinh năm <span>*</span></div>
                <div class="f-content">
                    <div class="row">
                        <div class="col-6 col-md-4 col-xl-2">
                            <div class="input-item">
                                <div class="input-item__inner">
                                    <label>Ngày</label>
                                    <input type="text" name="birthday[day]" value="{{ auth()->user()->birthday ? auth()->user()->birthday->format('d') : null }}" placeholder="Ngày sinh" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-xl-2">
                            <div class="input-item">
                                <div class="input-item__inner">
                                    <label>Tháng</label>
                                    <input type="text" name="birthday[month]" value="{{ auth()->user()->birthday ? auth()->user()->birthday->format('m') : null }}" placeholder="Tháng sinh" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-xl-3">
                            <div class="input-item">
                                <div class="input-item__inner">
                                    <label>Năm</label>
                                    <input type="text" name="birthday[year]" value="{{ auth()->user()->birthday ? auth()->user()->birthday->format('Y') : null }}" placeholder="Năm sinh" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="survey-question__item">
                <div class="f-label">Công việc hiện tại của bạn <span>*</span></div>
                <div class="f-content">
                    <div class="input-item">
                        <div class="input-item__inner">
                            <input type="text" name="job" placeholder="Công việc hiện tại của bạn" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <div v-for="(question, questionIndex) in questions" class="survey-question__item">
                <div class="f-label">
                    @{{ questionIndex + 1 }}. @{{ question.question }}
                    <span v-if="question.required == 1">*</span>
                </div>
                <input type="hidden" :name="'data[' + questionIndex + '][question]'" :value="question.question">
                <input type="hidden" :name="'data[' + questionIndex + '][type]'" :value="question.type">
                <template v-if="question.type == 'radio'">
                    <div class="f-content">
                        <div class="f-checkbox">
                            <label v-for="(option, optionIndex) in question.options" class="f-checkbox__item">
                                <input type="radio" :name="'data[' + questionIndex + '][answer]'" :value="option">
                                <span class="checkbox"></span>
                                <p>@{{ option }}</p>
                            </label>
                        </div>
                    </div>
                    <div class="f-label">Nhận xét</div>
                    <div class="f-content">
                        <div class="input-item">
                            <div class="input-item__inner">
                                <textarea :name="'data[' + questionIndex + '][comment]'" placeholder="Nhận xét" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </template>
                <div v-if="question.type == 'textarea'" class="f-content">
                    <div class="input-item">
                        <div class="input-item__inner">
                            <textarea :name="'data[' + questionIndex + '][answer]'" placeholder="Câu trả lời của bạn là" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="survey-question__footer">
            <button type="submit" class="btn">Gửi</button>
        </div>
    </div>
</div>
@endsection

@section('part_script')
<script>
    new Vue({
        el: '#survey',
        data: {
            questions: [],
        },
        mounted() {
            this.questions = JSON.parse(`{!! json_encode($data->data) !!}`);
        },
        methods: {

        },
    });

</script>
@endsection
