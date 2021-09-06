@extends('backend.master')

@section('title')
    @if (request()->routeIs('admin.practice_test.add'))
        Thêm bài viết mới
    @else
        Sửa bài viết
    @endif
@endsection

@section('content-header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">
                @if (request()->routeIs('admin.practice_test.add'))
                    Thêm đề thi mới
                @else
                    Sửa đề thi
                @endif
            </h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <div class="float-sm-right">
                <a href="{{ route('admin.practice_test.list') }}" class="btn btn-outline-primary"><i
                        class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
            </div>
        </div><!-- /.col -->
    </div>
@endsection

@section('content')
    @if ($errors->any())
        <div class="callout callout-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $msg)
                    <li>{{ $msg }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="callout callout-success">
            @if (is_array(session('success')))
                <ul class="mb-0">
                    @foreach (session('success') as $msg)
                        <li>{{ $msg }}</li>
                    @endforeach
                </ul>
            @else
                {{ session('success') }}
            @endif
        </div>
    @endif

    <div class="card" id="edit_practice">
        <form action="" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label>Chọn ngôn ngữ</label>
                    <select name="language_id" @change="onchangeLevel($event)"
                        class="form-control @error('language_id') is-invalid @enderror" v-model="model.language_id">
                        @foreach ($languages as $category)
                            <option value="{{ $category->id }}"
                                {{ ($data->category_id ?? old('category_id')) == $category->id ? 'selected' : '' }}>
                                {{ $category->title }}</option>
                        @endforeach
                    </select>
                    @error('language_id')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Chọn trình độ</label>
                    <select name="category_id" v-model="model.level"
                        class="form-control @error('category_id') is-invalid @enderror">
                        <option v-for="item in levels" v-bind:value="item.id" v-text="item.title"></option>
                    </select>
                    @error('category_id')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Tiêu đề</label>
                    <input v-model="model.title" type="text" name="title" placeholder="Tiêu đề" class="form-control">
                </div>

                <div class="row">
                    <div class="col-sm-1">
                        <div class="form-group">
                            <label>Lặp lại</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" v-model="model.loop" class="custom-control-input js-switch-enabled" id="cs-enabled-loop">
                                <label class="custom-control-label" for="cs-enabled-loop"></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Chọn ngày lặp</label>
                            <select multiple="" class="custom-select" v-model="model.selected_weekdays">
                                <option v-for="item in weekdays" :key="item.key" v-bind:value="item.key"
                                    v-text='item.value'></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-3 col-12">
                    <label>Chọn ngày cụ thể</label>
                    <div>
                        <datetimepicker format="DD-MM-YYYY" formatted="DD-MM-YYYY" only-date v-model="model.targetDate"
                            label="Chọn ngày" :no-label="true" />
                    </div>
                </div>
                <div class="form-group">
                    <label>Chọn ca thi</label><br>
                    <div class="form-row col-6" v-for="(item, idx) in model.shifts" :key="idx">
                        <div class="form-group col">
                            <div>
                                <datetimepicker v-model="item.start_time" v-bind:id="'start_time_'+idx"
                                    v-bind:name="'start_time_'+idx" only-time format="HH:mm" formatted="HH:mm"
                                    label="Bắt đầu" :no-label="true" />
                            </div>
                        </div>
                        <div class="form-group col">
                            <div>
                                <datetimepicker v-model="item.end_time" v-bind:id="'end_time_'+idx"
                                    v-bind:name="'end_time_'+idx" only-time format="HH:mm" label="Kết thúc"
                                    formatted="HH:mm" :no-label="true" />
                            </div>
                        </div>
                        <i class="fa fa-times close mt-2" @click="remove(model.shifts,item)"></i>
                    </div>
                    <button type="button" @click="addShift()" class="btn bg-gradient-primary mt-2">Thêm ca thi</button>
                </div>
                <div class="form-group col-md-3 col-12">
                    <label>Thời gian làm bài (phút)</label>
                    <input v-model="model.duration" type="text" name="duration" placeholder="Thời gian làm bài"
                        class="form-control currency">
                </div>
                <div></div>
                <div class="form-group">
                    <label>Câu hỏi</label>
                    <div>
                        <button type="button" @click="addSession()" class="btn bg-gradient-primary mb-2">Thêm phần</button>
                        <draggable v-if="model.sessions && model.sessions.length > 0" tag="div" :list="model.sessions"
                            class="list-group" handle=".handle">
                            <div class="list-group-item" v-for="(ss, sId) in model.sessions" :key="sId">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center flex-grow-1 col-md-2 col-sm-8">
                                        <i class="fa fa-align-justify handle"><span class="ml-2"
                                                v-text=""></span></i>
                                        {{-- <input type="text" class="form-control w-100" placeholder="Nhập tên phần"> --}}
                                        <multiselect v-model="ss.type" :show-labels="false" :allow-empty="false"
                                            :preselect-first="true" :options="sessionOptions.map(t => t.id)"
                                            placeholder="Chọn.." :custom-label="customSessionType"></multiselect>
                                    </div>
                                    <i @click="remove(model.sessions, ss)" class="fa fa-times close"></i>
                                </div>
                                <draggable v-if="ss.questions && ss.questions.length > 0 " tag="ul" :list="ss.questions"
                                    class="list-group" handle=".handle">
                                    <li v-if="item" class="list-group-item" v-for="(item, idx) in ss.questions" :key="idx">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center flex-grow-1 col-md-2 col-sm-8">
                                                <i class="fa fa-align-justify handle mr-3"></i>
                                                <select v-model="item.type" class="form-control"
                                                    placeholder="Chọn loại câu hỏi">
                                                    <option v-for="qt in questionTypes" v-bind:value="qt.id"
                                                        v-text="qt.name"></option>
                                                </select>
                                            </div>
                                            <i class="fa fa-times close" @click="remove(ss.questions,item)"></i>
                                        </div>
                                        <div v-text="'Câu ' + (idx + 1)"></div>
                                        <div class="mt-3">
                                            <input class="form-control form-control-lg" v-model="item.value" type="text"
                                                placeholder="Nhập câu hỏi">
                                        </div>
                                        <div>
                                            <input type="text" name="file" placeholder="Âm thanh" value=""
                                                class="d-none" v-bind:id="'ck-file-'+idx" v-model="item.media">
                                            <div class="input-group-append">
                                                <button type="button" class="input-group-text mt-2"
                                                    @click="selectFile('ck-file-'+ idx, 'ck-thumbnail-preview', ['mp3'])">Chọn
                                                    file</button>
                                            </div>
                                        </div>
                                        <div class="mt-3"
                                            v-if="item.answers && item.answers.length > 0 && item.type===1">
                                            <draggable tag="div" :list="item.answers" class="list-group"
                                                handle=".handle">
                                                <div class="list-group-item" v-if="item.answers"
                                                    v-for="(q, i) in item.answers" :key="i">
                                                    <div class="flex">
                                                        <i class="fa fa-align-justify handle"><span class="ml-2"
                                                                v-text="String.fromCharCode(65+i)"></span></i>
                                                        <i class="fa fa-times close" @click="remove(item.answers, q)"></i>
                                                    </div>
                                                    <div class="mt-3">
                                                        <div class="d-flex justify-content-between">
                                                            <input class="form-control mr-3" v-model="q.value" type="text">
                                                            <button type="button" class="btn float-right"
                                                                @click="checkCorrect(item.answers,q)"
                                                                v-bind:class="[!q.correct ? 'btn-default' : 'btn-primary']"><i
                                                                    class="fa fa-check"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </draggable>
                                        </div>
                                        <button v-if="item.type===1" @click="addAnswer(item)" type="button"
                                            class="btn bg-gradient-primary mt-2"><i class="fa fa-plus "></i></button>
                                    </li>
                                </draggable>
                                <button type="button" @click="addQuestion(ss)" class="btn bg-gradient-primary mt-2">Thêm câu hỏi</button>
                            </div>
                        </draggable>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" type="button" @click="create()"><i class="fas fa-save"></i> Lưu</button>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script>
        const initId = "{{ isset($languages[0]) ? $languages[0]->id : 'undefined' }}"
        console.log(initId)
        new Vue({
            el: '#edit_practice',
            data: {
                model: {
                    sessions: [],
                    shifts: [],
                    language_id: initId,
                    targetDate: null,
                    selected_weekdays: [],
                    level: null,
                    title: null,
                    duration: 0,
                    loop: false
                },
                levels: [],
                language_id: initId,
                start_time: null,
                end_time: null,
                weekdays: [{
                    key: 1,
                    value: 'Thứ hai'
                }, {
                    key: 2,
                    value: 'Thứ ba'
                }, {
                    key: 3,
                    value: 'Thứ tư'
                }, {
                    key: 4,
                    value: "Thứ năm"
                }, {
                    key: 5,
                    value: "Thứ sáu"
                }, {
                    key: 6,
                    value: "Thứ bảy"
                }, {
                    key: 0,
                    value: "Chủ nhật"
                }],
                selected_weekdays: [],
                targetDate: null,
                questions: [],
                sessionOptions: [{
                    id: 1,
                    name: "Đọc hiểu"
                }, {
                    id: 2,
                    name: "Nghe"
                }],
                value: null,
                questionTypes: [{
                    id: 1,
                    name: "Trắc nghiệm"
                }, {
                    id: 2,
                    name: "Tự luận"
                }],
            },
            mounted() {
                this.getCategories(this.language_id)
            },
            watch: {
                'model.shifts': {
                    handler: function(after, before) {
                        let res = 0;
                        for (let i = 0; i < after.length; i++) {
                            for (let k = i + 1; k < after.length; k++) {
                                if (after[i] != after[k]) {
                                    const range1 = moment.range(moment(after[i].start_time, "HH:mm"), moment(
                                        after[i].end_time, "HH:mm"));
                                    const range2 = moment.range(moment(after[k].start_time, "HH:mm"), moment(
                                        after[k].end_time, "HH:mm"));
                                    if (range2.overlaps(range1)) {
                                        res++;
                                    }
                                }
                            }
                        }
                    },
                    deep: true
                }
            },
            methods: {
                onchangeLevel: function(event) {
                    this.getCategories(event.target.value)
                },
                checkTime: function(event) {

                },
                addAnswer: function(question) {
                    question.answers.push({
                        value: '',
                        order: question.answers.length > 0 ? question.answers.length - 1 : 0,
                        correct: false
                    });
                },
                addQuestion: function(ss) {
                    ss.questions.push({
                        order: ss.questions.length > 0 ? ss.questions.length - 1 : 0,
                        value: '',
                        answers: [],
                        type: 1,
                        media: null,
                    })
                },
                addSession: function(id) {
                    this.model.sessions.push({
                        questions: [],
                        id: id,
                        type: 1
                    });
                },
                checkCorrect: function(lst, item) {
                    lst.filter(i => i != item).forEach(i => {
                        i.correct = false;
                    })
                    item.correct = !item.correct;
                },
                remove: function(lst, item) {
                    const index = lst.indexOf(item);
                    if (index > -1) {
                        lst.splice(index, 1);
                    }
                },
                getCategories: function(id) {
                    axios.get('{{ route('admin.practice_test.get_categories') }}', {
                        params: {
                            type: "level",
                            parent_id: id
                        }
                    }).then(res => {
                        this.levels = res.data;
                    });
                },
                customSessionType: function(v) {
                    let item = this.sessionOptions.find((i) => i.id === v) || {
                        name: ""
                    }
                    return item['name'];
                },
                selectFile(id, prv, types) {
                    window.selectFileWithCKFinder(id, prv, types, (res) => {
                        console.log(res)
                    })
                },
                addShift() {
                    this.model.shifts.push({
                        start_time: null,
                        end_time: null
                    })
                },
                checkValid() {
                    return true;
                },
                create() {
                    if (!this.checkValid()) return
                    const formData = new FormData();
                    formData.append('data', JSON.stringify(this.model))
                    axios.post('{{ route('admin.practice_test.submitAdd') }}',
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
