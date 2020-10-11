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
        <a href="{{ route('course', [ 'slug' => $course->slug ]) }}" target="_blank"><small><em>{{ route('course', [ 'slug' => $course->slug ]) }}</em></small></a>
        <br>
        {{ $course->description }}
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
            <div class="form-group">
                <label>Mô tả</label>
                <textarea name="description" rows="3" placeholder="Mô tả" class="form-control @error('description') is-invalid @enderror">{{ $data->description ?? null }}</textarea>
                @error('description')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <hr>
            <div id="survey">
                <table class="table table-borderless table-striped">
                    <tbody>
                        <tr v-for="(question, questionIndex) in questions">
                            <td>
                                <div class="form-group">
                                    <label>Câu hỏi số @{{ questionIndex + 1 }}</label>
                                    <textarea v-model="question.question" :name="'data[' + questionIndex + '][question]'" rows="3" class="form-control" :placeholder="'Câu hỏi số ' + (questionIndex + 1)"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Bắt buộc</label>
                                    <div class="form-check">
                                        <input v-model="question.required" type="radio" :name="'data[' + questionIndex + '][required]'" :value="true" :id="'question-required-' + questionIndex + '-true'">
                                        <label class="form-check-label" :for="'question-required-' + questionIndex + '-true'">Bắt buộc</label>
                                    </div>
                                    <div class="form-check">
                                        <input v-model="question.required" type="radio" :name="'data[' + questionIndex + '][required]'" :value="false" :id="'question-required-' + questionIndex + '-false'">
                                        <label class="form-check-label" :for="'question-required-' + questionIndex + '-false'">Không bắt buộc</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Hình thức</label>
                                    <div class="form-check">
                                        <input v-model="question.type" type="radio" :name="'data[' + questionIndex + '][type]'" value="textarea" :id="'question-type-' + questionIndex + '-textarea'">
                                        <label class="form-check-label" :for="'question-type-' + questionIndex + '-textarea'">Điền câu trả lời</label>
                                    </div>
                                    <div class="form-check">
                                        <input v-model="question.type" type="radio" :name="'data[' + questionIndex + '][type]'" value="radio" :id="'question-type-' + questionIndex + '-radio'">
                                        <label class="form-check-label" :for="'question-type-' + questionIndex + '-radio'">Chọn câu trả lời</label>
                                    </div>
                                </div>
                                <div v-if="question.type == 'radio'" class="form-group">
                                    <label>Câu trả lời</label>
                                    <table class="table table-borderless">
                                        <tr v-for="(option, optionIndex) in question.options">
                                            <td class="align-middle">
                                                <input v-model="question.options[optionIndex]" type="text" :name="'data[' + questionIndex + '][options][' + optionIndex + ']'" class="form-control" :placeholder="'Câu trả lời thứ ' + (optionIndex + 1)">
                                            </td>
                                            <td class="align-middle">
                                                <button type="button" class="btn btn-sm btn-danger" @click="question.options.splice(optionIndex, 1)"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </table>
                                    <button type="button" class="btn btn-info btn-sm" @click="addOption(question)">Thêm câu trả lời</button>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-sm btn-link text-red" @click="questions.splice(questionIndex, 1)">Xóa câu hỏi</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-info" @click="addQuestion"><i class="fas fa-plus"></i> Thêm câu hỏi</button>
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
        el: '#survey',
        data: {
            questions: [],
            defaultQuestion: {
                question: '',
                type: 'textarea',
                options: [],
                required: false,
            },
        },
        mounted() {
            this.questions = JSON.parse(`{!! json_encode($data->data ?? []) !!}`).map(question => {
                if (question.options == undefined) question.options = [];
                return question;
            });
        },
        methods: {
            addQuestion() {
                this.questions.push(_.cloneDeep(this.defaultQuestion));
            },
            addOption(question) {
                question.options.push('');
            },
        },
    });

</script>
@endsection
