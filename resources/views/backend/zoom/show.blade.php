@extends('backend.master')

@section('title')
Quản lý Meeting
@endsection

@section('content-header')
<style>
    .card.info__zoom {
        width: 700px;
        margin: auto;
    }
    #form__info__zoom {
        /* width: 600px; */
    }
    #form__info__zoom tr>th, #form__info__zoom tr>td {
        padding-bottom: 1rem;
    }
    #form__info__zoom tr>th {
        padding-right: 1rem;
    }
</style>
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Chi tiết "{{ $meeting['data']['topic'] }}"</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.zoom.index') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
        </div>
    </div><!-- /.col -->
</div>
@endsection

@section('content')
<br/>
<div class="row">
    <div class="col-lg-12">
        <div class="card info__zoom">
            <div class="card-body">
                <form action="{{ route('admin.zoom.update', ['id' => $meeting['data']['id']]) }}" method="POST">
                    @csrf
                    <table id="form__info__zoom">
                        <tr>
                            <th>ID lớp học</th>
                            <td>{{ $meeting['data']['id'] }}</td>
                        </tr>
                        <tr>
                            <th>Chủ đề</th>
                            <td><input type="text" class="form-control form-control-sm" name="topic" placeholder="Topic" value="{{ $meeting['data']['topic'] }}"></td>
                        </tr>
                        <tr>
                            <th>Mô tả</th>
                            <td><textarea class="form-control form-control-sm" name="agenda">{{ $meeting['data']['agenda'] }}</textarea></td>
                        </tr>
                        <tr>
                            <th>Thời gian</th>
                            <td><input type="datetime-local" class="form-control form-control-sm" name="start_time" value="{{ date("Y-m-d\TH:i:s", strtotime($meeting['data']['start_time'])) }}"></td>
                        </tr>
                        <tr>
                            <th>Thời lượng (phút)</th>
                            <td><input type="number" class="form-control form-control-sm" name="duration" placeholder="Phút" value="{{ $meeting['data']['duration'] }}"></td>
                        </tr>
                        <tr>
                            <th>Bảo mật</th>
                            <td>
                                <div class="form-row" align-items-center>
                                    <div class="col-auto">Mật mã</div>
                                    <div class="col-auto"><input type="password" class="form-control form-control-sm" name="password" placeholder="Mật khẩu" value="{{ $meeting['data']['password'] }}"></div>
                                    <div class="col-auto"><button class="btn btn-sm btn-dark" id="changeTypePassword" type="button">Ẩn/Hiện</button></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Liên kết mời</th>
                            <td>{{ $meeting['data']['join_url'] }}</td>
                        </tr>
                    </table><br/>
                    <div class="text-center">
                        @if ($meeting['data']['status'] == 'waiting')
                        <a class="btn btn-danger" href="{{ $meeting['data']['start_url'] }}" target="_blank">Bắt đầu lớp học</a>
                        @else
                        <a class="btn btn-danger disabled" href="{{ $meeting['data']['start_url'] }}" target="_blank">Lớp học đang diễn ra ...</a>
                        @endif
                        <button class="btn btn-outline-primary" type="submit">Cập nhật thông tin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div><br/>
<div class="row">
    <div class="col-lg-12 text-center"></div>
    <div class="col-lg-12">
        <div class="card collapsed-card">
            <div class="card-header">
                <h5 class="card-title">Mời học viên</h5>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.zoom.send_email_notify') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_meeting" value="{{ $meeting['data']['id'] }}">
                    <div id="js-invite-course">
                        <div class="card">
                            <div class="card-header">Học viên trong khoá học</div>
                            <div class="card-body">
                                <table class="table table-striped table-borderless">
                                    <tr v-for="item in inviteCourses" :key="item.id">
                                        <td>
                                            @{{ item.id }}
                                            <input type="hidden" name="__invite_courses[]" :value="item.id">
                                        </td>
                                        <td>
                                            <img :src="item.thumbnail" class="img-thumbnail">
                                        </td>
                                        <td>@{{ item.title }}</td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm" @click="deleteItem(item.id)"><i class="far fa-trash-alt"></i> Xóa</button>
                                        </td>
                                    </tr>
                                </table>
                                <hr>
                                <div class="text-center">
                                    <button type="button" class="btn btn-info" @click="showAddItemModal"><i class="fas fa-plus"></i> Thêm</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" tabindex="-1" id="js-invite-course-modal">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Chọn khóa học</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Tìm kiếm khóa học" v-model="keyword">
                                        </div>
                                        <div class="text-center" v-if="isSearching">
                                            <div class="spinner-border">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                        <table class="table table-striped table-borderless" v-else>
                                            <tr v-for="item in searchResult" :key="item.id">
                                                <td>@{{ item.id }}</td>
                                                <td>
                                                    <img :src="item.thumbnail" class="img-thumbnail">
                                                </td>
                                                <td>@{{ item.title }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-sm" @click="addItem(item)" :disabled="courseIds.includes(item.id)"><i class="fas fa-plus"></i></button>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="js-invite-user">
                        <div class="card">
                            <div class="card-header">Học viên</div>
                            <div class="card-body">
                                <table class="table table-striped table-borderless">
                                    <tr v-for="item in inviteCourses" :key="item.id">
                                        <td>
                                            @{{ item.id }}
                                            <input type="hidden" name="__invite_users[]" :value="item.id">
                                        </td>
                                        <td>
                                            <img :src="item.avatar" class="img-thumbnail">
                                        </td>
                                        <td>@{{ item.username }}</td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm" @click="deleteItem(item.id)"><i class="far fa-trash-alt"></i> Xóa</button>
                                        </td>
                                    </tr>
                                </table>
                                <hr>
                                <div class="text-center">
                                    <button type="button" class="btn btn-info" @click="showAddItemModal"><i class="fas fa-plus"></i> Thêm</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" tabindex="-1" id="js-invite-user-modal">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Chọn học viên</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Tìm kiếm học viên" v-model="keyword">
                                        </div>
                                        <div class="text-center" v-if="isSearching">
                                            <div class="spinner-border">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                        <table class="table table-striped table-borderless" v-else>
                                            <tr v-for="item in searchResult" :key="item.id">
                                                <td>@{{ item.id }}</td>
                                                <td>
                                                    <img :src="item.avatar" class="img-thumbnail" v-if="item.avatar">
                                                    <img src="{{ asset('images/avatar-base.jpg') }}" class="img-thumbnail" v-else>
                                                </td>
                                                <td>
                                                    <p>Tài khoản: <u>@{{ item.username }}</u></p>
                                                    <p v-if="item.name">Tên: <u>@{{ item.name }}</u></p>
                                                    <p v-if="item.email">Email: <u>@{{ item.email }}</u></p>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-sm" @click="addItem(item)" :disabled="courseIds.includes(item.id)"><i class="fas fa-plus"></i></button>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="right"><button type="submit" class="btn btn-primary">Gửi email</button></div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $("#changeTypePassword").click(function(){
        if ($('input[name="password"]').get(0).type == 'password') {
            $('input[name="password"]').get(0).type = 'text';
        } else {
            $('input[name="password"]').get(0).type = 'password';
        };
    });
</script>
<script>
    new Vue({
        el: '#js-invite-course',
        data: {
            courseIds: [],
            inviteCourses: [],
            searchTimer: undefined,
            searchResult: [],
            keyword: undefined,
            isSearching: false,
        },
        mounted() {
        },
        methods: {
            showAddItemModal() {
                $('#js-invite-course-modal').modal('show');
            },
            addItem(item) {
                this.inviteCourses.push(item);
                this.courseIds.push(item.id);
                console.log(this.courseIds);
            },
            deleteItem(id) {
                const index = this.inviteCourses.findIndex(x => x.id == id);
                this.inviteCourses.splice(index, 1);
                this.courseIds.splice(index, 1);
            },
        },
        watch: {
            keyword(newVal, oldVal) {
                this.isSearching = true;
                clearTimeout(this.searchTimer);
                this.searchTimer = setTimeout(() => {
                    axios.get('{{ route("admin.course.search_course") }}', {
                        params: {
                            keyword: this.keyword
                        }
                    }).then(res => {
                        this.searchResult = res;
                    }).then(() => {
                        this.isSearching = false;
                    });
                }, 1000);
            }
        }
    });
</script>
<script>
    new Vue({
        el: '#js-invite-user',
        data: {
            courseIds: [],
            inviteCourses: [],
            searchTimer: undefined,
            searchResult: [],
            keyword: undefined,
            isSearching: false,
        },
        mounted() {
        },
        methods: {
            showAddItemModal() {
                $('#js-invite-user-modal').modal('show');
            },
            addItem(item) {
                this.inviteCourses.push(item);
                this.courseIds.push(item.id);
                console.log(this.courseIds);
            },
            deleteItem(id) {
                const index = this.inviteCourses.findIndex(x => x.id == id);
                this.inviteCourses.splice(index, 1);
                this.courseIds.splice(index, 1);
            },
        },
        watch: {
            keyword(newVal, oldVal) {
                this.isSearching = true;
                clearTimeout(this.searchTimer);
                this.searchTimer = setTimeout(() => {
                    axios.get('{{ route("admin.user.search_user") }}', {
                        params: {
                            keyword: this.keyword
                        }
                    }).then(res => {
                        this.searchResult = res;
                    }).then(() => {
                        this.isSearching = false;
                    });
                }, 1000);
            }
        }
    });
</script>
@endsection
