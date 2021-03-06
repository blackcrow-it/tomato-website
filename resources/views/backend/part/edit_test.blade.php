@extends('backend.master')

@section('title')
Sửa đầu mục
@endsection

@section('content-header')
<div class="row">
    <div class="col-sm-2">
        <img src="{{ $course->thumbnail }}">
    </div>
    <div class="col-sm-5">
        <strong>{{ $course->title }}</strong>
        <br>
        <a href="{{ route('course', [ 'slug' => $course->slug, 'id' => $course->id ]) }}" target="_blank"><small><em>{{ route('course', [ 'slug' => $course->slug, 'id' => $course->id ]) }}</em></small></a>
        <br>
        {!! $course->description !!}
    </div>
    <div class="col-sm-5">
        <strong>{{ $lesson->title }}</strong>
    </div>
</div>
<hr>
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Sửa đầu mục</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.part.list', [ 'lesson_id' => $lesson->id ]) }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
        </div>
    </div><!-- /.col -->
</div>
@endsection

@section('content')
@if($errors->any())
    <div class="callout callout-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $msg)
                <li>{{ $msg }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="callout callout-success">
        @if(is_array(session('success')))
            <ul class="mb-0">
                @foreach(session('success') as $msg)
                    <li>{{ $msg }}</li>
                @endforeach
            </ul>
        @else
            {{ session('success') }}
        @endif
    </div>
@endif

<div class="card">
    <form action="" method="POST" class="js-main-form">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Loại đầu mục</label>
                <div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="radio" id="cr-type-1" value="{{ \App\Constants\PartType::VIDEO }}" {{ $part->type == \App\Constants\PartType::VIDEO ? 'checked' : '' }} disabled>
                        <label class="form-check-label" for="cr-type-1">Video</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="radio" id="cr-type-2" value="{{ \App\Constants\PartType::YOUTUBE }}" {{ $part->type == \App\Constants\PartType::YOUTUBE ? 'checked' : '' }} disabled>
                        <label class="form-check-label" for="cr-type-2">Youtube</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="radio" id="cr-type-3" value="{{ \App\Constants\PartType::CONTENT }}" {{ $part->type == \App\Constants\PartType::CONTENT ? 'checked' : '' }} disabled>
                        <label class="form-check-label" for="cr-type-3">Bài viết</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="radio" id="cr-type-4" value="{{ \App\Constants\PartType::TEST }}" {{ $part->type == \App\Constants\PartType::TEST ? 'checked' : '' }} disabled>
                        <label class="form-check-label" for="cr-type-4">Trắc nghiệm</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="radio" id="cr-type-5" value="{{ \App\Constants\PartType::SURVEY }}" {{ $part->type == \App\Constants\PartType::SURVEY ? 'checked' : '' }} disabled>
                        <label class="form-check-label" for="cr-type-5">Khảo sát</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Tiêu đề</label>
                <input type="text" name="title" placeholder="Tiêu đề" value="{{ $part->title }}" class="form-control @error('title') is-invalid @enderror">
                @error('title')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <hr>
            <div id="questions">
                <table class="table table-bordered table-striped">
                    <tr v-for="(question, questionIndex) in questions">
                        <td>
                            <h4>Câu hỏi số @{{ questionIndex + 1 }}</h4>
                            <div v-if="question.type == undefined" class="form-group">
                                <label>Loại câu hỏi</label> 
                                <div class="form-check">
                                    <input v-model="question.type" type="radio" :name="'data[' + questionIndex + '][type]'" value="multiple-choice" :id="'question-type-1-' + questionIndex" @change="loadDefaultQuestion(questionIndex)">
                                    <label class="form-check-label" :for="'question-type-1-' + questionIndex">Lựa chọn đáp án</label>
                                </div>
                                <div class="form-check">
                                    <input v-model="question.type" type="radio" :name="'data[' + questionIndex + '][type]'" value="correct-word-position" :id="'question-type-2-' + questionIndex" @change="loadDefaultQuestion(questionIndex)">
                                    <label class="form-check-label" :for="'question-type-2-' + questionIndex">Kéo từ còn thiếu vào vị trí đúng</label>
                                </div>
                                <div class="form-check">
                                    <input v-model="question.type" type="radio" :name="'data[' + questionIndex + '][type]'" value="translate-text" :id="'question-type-3-' + questionIndex" @change="loadDefaultQuestion(questionIndex)">
                                    <label class="form-check-label" :for="'question-type-3-' + questionIndex">Dịch đoạn văn</label>
                                </div>
                            </div>
                            <input v-else v-model="question.type" type="hidden" :name="'data[' + questionIndex + '][type]'">
                            <template v-if="question.type != undefined">
                                <div class="form-group">
                                    <label>Câu hỏi</label>
                                    <textarea v-model="question.question" :name="'data[' + questionIndex + '][question]'" class="editor"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>File âm thanh (nếu có)</label>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <input type="file" accept="audio/*" :id="'question-audio-index-' + questionIndex" class="d-none" @change="inputAudioFileChanged(questionIndex)">
                                            <input v-model="question.audio" type="hidden" :name="'data[' + questionIndex + '][audio]'">
                                            <div v-if="question.uploadingAudio" class="progress mb-1">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" :style="{ 'width': question.uploadingAudioPercent + '%' }">@{{ question.uploadingAudioPercent }}%</div>
                                            </div>
                                            <audio v-if="question.audio" :src="question.audio" controls controlsList="nodownload"></audio>
                                            <button v-if="question.audio" type="button" class="btn btn-sm btn-danger" :disabled="question.uploadingAudio" @click="deleteAudioFile(questionIndex)">Xóa file</button>
                                            <button v-else type="button" class="btn btn-sm btn-info" :disabled="question.uploadingAudio" @click="openInputAudioFile(questionIndex)">Chọn file</button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <template v-if="question.type == 'multiple-choice'">
                                <div class="form-group">
                                    <label>Đáp án</label>
                                    <table class="table table-bordered">
                                        <tr v-for="(option, optionIndex) in question.options">
                                            <td class="align-middle">
                                                <label class="mb-0">
                                                    <input v-model="question.correct" type="radio" :name="'data[' + questionIndex + '][correct]'" :value="optionIndex">
                                                    @{{ String.fromCharCode(65 + optionIndex) }}
                                                </label>
                                            </td class="align-middle">
                                            <td class="align-middle">
                                                <input v-model="question.options[optionIndex]" type="text" :name="'data[' + questionIndex + '][options][' + optionIndex + ']'" class="form-control" :placeholder="'Đáp án ' + String.fromCharCode(65 + optionIndex) + ', tick vào ô bên trái nếu là đáp án đúng'">
                                            </td>
                                            <td class="align-middle">
                                                <button type="button" class="btn btn-sm btn-danger" @click="question.options.splice(optionIndex, 1)"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </table>
                                    <button type="button" class="btn btn-info btn-sm" @click="addOption(question)">Thêm đáp án</button>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-sm btn-link" @click="questions.splice(questionIndex, 1)">Xóa câu hỏi</button>
                                </div>
                            </template>
                            <template v-if="question.type == 'correct-word-position'">
                                <div class="form-group">
                                    <label>Các thành phần trong câu</label>
                                    <small><i class="fas fa-question-circle text-warning" data-toggle="popover" data-html="true" data-content="- Tick vào ô bên trái nếu đó là từ còn thiếu.<br>- Ô trống sẽ được chèn vào giữa các thành phần trong câu."></i></small>
                                    <table class="table table-bordered">
                                        <tr v-for="(option, optionIndex) in question.options">
                                            <td class="align-middle">
                                                <label class="mb-0">
                                                    <input v-model="question.correct" type="radio" :name="'data[' + questionIndex + '][correct]'" :value="optionIndex">
                                                </label>
                                            </td class="align-middle">
                                            <td class="align-middle">
                                                <input v-model="question.options[optionIndex]" type="text" :name="'data[' + questionIndex + '][options][' + optionIndex + ']'" class="form-control" placeholder="Tick vào ô bên trái nếu là từ còn thiếu">
                                            </td>
                                            <td class="align-middle">
                                                <button type="button" class="btn btn-sm btn-danger" @click="question.options.splice(optionIndex, 1)"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </table>
                                    <button type="button" class="btn btn-info btn-sm" @click="addOption(question)">Thêm thành phần</button>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-sm btn-link" @click="questions.splice(questionIndex, 1)">Xóa câu hỏi</button>
                                </div>
                            </template>
                            <template v-if="question.type == 'translate-text'">
                                <div class="form-group">
                                    <label>Đáp án</label>
                                    <table class="table table-bordered">
                                        <tr v-for="(option, optionIndex) in question.options">
                                            <td class="align-middle" style= "display:none">
                                                <label class="mb-0">
                                                    <input v-model="question.correct" :name="'data[' + questionIndex + '][correct]'">
                                                </label>
                                            </td class="align-middle">
                                            <td class="align-middle">
                                                <input v-model="question.options[optionIndex]" type="text" :name="'data[' + questionIndex + '][options][' + optionIndex + ']'" class="form-control" :placeholder="'Đáp án ' + String.fromCharCode(65 + optionIndex) + ', tick vào ô bên trái nếu là đáp án đúng'">
                                            </td>
                                            <td class="align-middle">
                                                <button type="button" class="btn btn-sm btn-danger" @click="question.options.splice(optionIndex, 1)"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </table>
                                    <button type="button" class="btn btn-info btn-sm" @click="addOption(question)">Thêm đáp án</button>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-sm btn-link" @click="questions.splice(questionIndex, 1)">Xóa câu hỏi</button>
                                </div>
                            </template>
                        </td>
                    </tr>
                </table>
                <button type="button" class="btn btn-info" @click="addQuestion"><i class="fas fa-plus"></i> Thêm câu hỏi</button>
            </div>
            <hr>
            <div class="form-group">
                <label>Số câu cần trả lời đúng</label>
                <input type="text" name="correct_requirement" placeholder="Số câu cần trả lời đúng" value="{{ $data->correct_requirement ?? null }}" class="form-control currency @error('correct_requirement') is-invalid @enderror">
                @error('correct_requirement')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Sắp xếp ngẫu nhiên câu hỏi và đáp án</label>
                <div class="form-check">
                    <input type="radio" name="random_enabled" value="1" id="random_enabled_true" {{ ($data->random_enabled ?? false) == true ? 'checked' : null }}>
                    <label class="form-check-label" for="random_enabled_true">Có</label>
                </div>
                <div class="form-check">
                    <input type="radio" name="random_enabled" value="0" id="random_enabled_false" {{ ($data->random_enabled ?? false) == false ? 'checked' : null }}>
                    <label class="form-check-label" for="random_enabled_false">Không</label>
                </div>
                @error('random_enabled')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary"><i class="fas fa-save"></i> Lưu</button>
        </div>
    </form>
</div>
@endsection

@section('script')
<script>
    new Vue({
        el: '#questions',
        data: {
            questions: [],
            defaultQuestion: [
                {
                    type: 'multiple-choice',
                    question: '',
                    options: [
                        '', '', '', ''
                    ],
                    correct: 0,
                    audio: undefined,
                    uploadingAudio: false,
                    uploadingAudioPercent: 0,
                },
                {
                    type: 'correct-word-position',
                    question: '', 
                    options: [
                        '', '', '', ''
                    ],
                    correct: 0,
                    audio: undefined,
                    uploadingAudio: false,
                    uploadingAudioPercent: 0,
                },
                {
                    type: 'translate-text',
                    question: '', 
                    options: [
                        '', '', '', ''
                    ],
                    correct: false,
                    audio: undefined,
                    uploadingAudio: false,
                    uploadingAudioPercent: 0,
                },
            ],
        },
        mounted() {
            axios.get("{{ route('admin.part_test.get_data', [ 'part_id' => $part->id ]) }}").then(res => {
                if(res.length == 0){
                    this.questions = array();
                }
                this.questions = res;
                this.$nextTick(() => {
                    tooltipInit();
                    ckeditorInit();
                });
            });
            // Chưa hiểu đoạn này trước để làm gì (?)
            // axios.post(location.href, $('.js-main-form').serialize()).catch(() => {
            //     alert('Có lỗi xảy ra. Vui lòng load lại page.');
            // });
        },
        methods: {
            addQuestion() {
                this.questions.push({
                    type : undefined
                });
            },
            loadDefaultQuestion(index) {
                const type = this.questions[index].type;
                this.$set(this.questions, index, _.cloneDeep(this.defaultQuestion.find(x => x.type == type)));

                this.$nextTick(() => {
                    tooltipInit();
                    ckeditorInit();
                });
            },
            addOption(question) {
                question.options.push('');
            },
            openInputAudioFile(index) {
                $('#question-audio-index-' + index).trigger('click');
            },
            inputAudioFileChanged(index) {
                const files = $('#question-audio-index-' + index).prop('files');
                if (files.length == 0) return;

                const formData = new FormData();
                formData.append('part_id', '{{ $part->id }}');
                formData.append('audio', files[0]);

                this.$set(this.questions[index], 'uploadingAudio', true);
                axios.post('{{ route("admin.part_test.upload_audio") }}', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    onUploadProgress: event => {
                        const percent = Math.round((event.loaded * 100) / event.total);
                        this.$set(this.questions[index], 'uploadingAudioPercent', percent);
                    }
                }).then(res => {
                    this.$set(this.questions[index], 'audio', res.src);
                }).catch(res => {
                    alert('Lỗi xảy ra khi upload audio.');
                }).then(() => {
                    this.$set(this.questions[index], 'uploadingAudio', false);
                });
            },
            deleteAudioFile(index) {
                this.$set(this.questions[index], 'uploadingAudio', true);
                axios.post('{{ route("admin.part_test.delete_audio") }}', {
                    part_id: '{{ $part->id }}',
                    src: this.questions[index].audio
                }).then(res => {
                    this.$set(this.questions[index], 'audio', undefined);
                }).catch(res => {

                }).then(() => {
                    this.$set(this.questions[index], 'uploadingAudio', false);
                });
            },
        },
    });

</script>
@endsection
