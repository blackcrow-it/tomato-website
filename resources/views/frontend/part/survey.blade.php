@extends('frontend.part.master')

@section('content')
<div class="learningLesson__quiz" id="survey">
    <div class="quiz-wrap">
        <div class="survey-question__header">
            <h2 class="f-title">{{ $data->title }}</h2>
            <div class="f-text">
                <p>{{ $data->description }}</p>
                <p class="text-primary">*Bắt buộc</p>
            </div>
        </div>
        <div class="survey-question__content">
            <div class="survey-question__item">
                <h4 class="f-label">Họ và tên</h4>
                <div class="f-content">
                    {{ auth()->user()->name }}
                </div>
            </div>
            <div v-for="(question, questionIndex) in questions" class="survey-question__item">
                <h4 class="f-label">@{{ questionIndex + 1 }}. @{{ question.question }}</h4>
                <input type="hidden" :name="'data[' + questionIndex + '][question]'" :value="question.question">
                <div v-if="question.type == 'radio'" class="f-content">
                    <div class="f-checkbox">
                        <label v-for="(option, optionIndex) in question.options" class="f-checkbox__item">
                            <input type="radio" :name="'data[' + questionIndex + '][answer]'" :value="option">
                            <span class="checkbox"></span>
                            <p>@{{ option }}</p>
                        </label>
                    </div>
                </div>
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
