@extends('frontend.part.master')

@section('content')
<div class="learningLesson__quiz" id="survey">
    <div class="quiz-wrap">
        <div class="survey-question__header">
            <div class="f-title">{{ $part->title }}</div>
            <div class="f-text">
                <p>{{ $data->description }}</p>
                <p class="text-primary">* Bắt buộc</p>
            </div>
        </div>
        <div class="survey-question__content">
            {{-- <div class="survey-question__item">
                <div class="f-label label-required-survey">Họ và tên <span>*</span></div>
                <div class="f-content">
                    <div class="input-item">
                        <div class="input-item__inner">
                            <input type="text" name="name" value="{{ auth()->user()->name }}" placeholder="Họ và tên" class="form-control required-survey" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="survey-question__item">
                <div class="f-label label-required-survey">Email <span>*</span></div>
                <div class="f-content">
                    <div class="input-item">
                        <div class="input-item__inner">
                            <input type="email" name="email" value="{{ auth()->user()->email }}" placeholder="Email" class="form-control required-survey" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="survey-question__item">
                <div class="f-label label-required-survey">Số điện thoại <span>*</span></div>
                <div class="f-content">
                    <div class="input-item">
                        <div class="input-item__inner">
                            <input type="tel" name="phone" value="{{ auth()->user()->phone }}" placeholder="Số điện thoại" class="form-control required-survey" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="survey-question__item">
                <div class="f-label label-required-survey">Ngày sinh <span>*</span></div>
                <div class="f-content">
                    <div class="input-item">
                        <div class="input-item__inner">
                            <input type="date" name="birthday" value="{{ auth()->user()->birthday }}" placeholder="Ngày sinh" class="form-control required-survey" required>
                        </div>
                    </div>
                </div>
            </div> --}}


            <div v-for="(question, questionIndex) in questions" class="survey-question__item">
                <div class="f-label" :id="'label-survey-' + questionIndex">
                    @{{ questionIndex + 1 }}. @{{ question.question }}
                    <span v-if="question.required == 1">*</span>
                </div>
                <input type="hidden" :name="'data[' + questionIndex + '][question]'" :value="question.question">
                <input type="hidden" :name="'data[' + questionIndex + '][type]'" :value="question.type">
                <template v-if="question.type == 'radio'">
                    <div class="f-content">
                        <div class="f-checkbox">
                            <label v-for="(option, optionIndex) in question.options" class="f-checkbox__item">
                                <input type="radio" :name="'data[' + questionIndex + '][answer]'" :value="optionIndex">
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
            <button type="button" class="btn" @click="sendSurvey">Gửi</button>
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
            checkRequired() {
                let result = true;
                $.each(this.questions, function (indexInArray, valueOfElement) {
                    if (valueOfElement["required"] == 1) {
                        if (valueOfElement["type"] == "textarea") {
                            if ($("[name='data[" + indexInArray + "][answer]']").val() == '') {
                                result = false;
                                $("#label-survey-" + indexInArray).css("color", "#e71d36");
                            } else {
                                $("#label-survey-" + indexInArray).css("color", "#77af41");
                            }
                        } else if (valueOfElement["type"] == "radio") {
                            if ($("[name='data[" + indexInArray + "][answer]']:checked").length == 0) {
                                result = false;
                                $("#label-survey-" + indexInArray).css("color", "#e71d36");
                            } else {
                                $("#label-survey-" + indexInArray).css("color", "#77af41");
                            }
                        }
                    }
                    if (valueOfElement["type"] == "textarea") {
                        valueOfElement['answer'] = $("[name='data[" + indexInArray + "][answer]']").val();
                    } else if (valueOfElement["type"] == "radio") {
                        valueOfElement['answer'] = $("[name='data[" + indexInArray + "][answer]']:checked").val();
                        valueOfElement['comment'] = $("[name='data[" + indexInArray + "][comment]']").val();
                    }
                });
                $('.required-survey').each(function(i, obj) {
                    if (obj.value == undefined || obj.value == '') {
                        result = false;
                    }
                });
                return result;
            },
            sendSurvey() {
                if (this.checkRequired()) {
                    axios.post(
                        "{{ route('api.survey.add') }}",
                        { part_id: {{$part->id}}, data: this.questions }
                    )
                    .then(function (response) {
                        axios.post("{{ route('part.set_complete') }}", { part_id: {{$part->id}} })
                        .then(function (response) {
                            bootbox.alert('<h1>Hoàn thành!</h1>Chúng tôi đã nhận được phiếu khảo sát của bạn.', function(){
                                window.location.href = "{{route('part', ['id' => $next_part->id]) }}";
                            });
                        })
                        .catch(function (error) {
                            bootbox.alert("<h1>Cảnh báo!</h1>Lỗi không hoàn thành được phiếu khảo sát.");
                        })
                    })
                    .catch(function (error) {
                        bootbox.alert("<h1>Cảnh báo!</h1>Lỗi không gửi được phiếu khảo sát. Vui lòng thử lại sau");
                    })
                } else {
                    bootbox.alert('<h1>Nhắc nhở!</h1>Những mục <b>bắt buộc</b> còn trống. Vui lòng hãy điền đầy đủ.');
                }
            }
        },
    });

</script>
@endsection
