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
        width: 600px;
    }
    /* #form__info__zoom tr {
        padding-bottom: 1rem;
    } */
    #form__info__zoom tr>th, #form__info__zoom tr>td {
        padding-bottom: 1rem;
    }
    #form__info__zoom tr>th {
        padding-right: 1rem;
    }
</style>
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Chi tiết "{{ $meeting['topic'] }}"</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.zoom.meetings', ['id' => $meeting['owner_id']]) }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
        </div>
    </div><!-- /.col -->
</div>
@endsection

@section('content')
<br/>
<div class="row">
    <div class="col-lg-12">
        <div class="card info__zoom">
            <div class="card-body" id="js-edit-zoom">
                <form action="{{ route('admin.zoom.update', ['id' => $meeting['meeting_id']]) }}" method="POST">
                    @csrf
                    <table id="form__info__zoom">
                        <tr>
                            <td style="width: 20%">ID lớp học</td>
                            <td style="width: 80%">{{ $meeting['meeting_id'] }}</td>
                        </tr>
                        <tr>
                            <td>Chủ đề</td>
                            <td><input type="text" class="form-control form-control-sm" name="topic" placeholder="Topic" value="{{ $meeting['topic'] }}"></td>
                        </tr>
                        <tr>
                            <td>Mô tả</td>
                            <td><textarea class="form-control form-control-sm" name="agenda">{{ $meeting['agenda'] }}</textarea></td>
                        </tr>
                        <tr>
                            <td>Thời gian</td>
                            <td>
                                @if ($meeting['type'] == 8)
                                    @php
                                        $occurrences = json_decode($meeting['occurrences'], true);
                                        $recurrence = json_decode($meeting['recurrence'], true);
                                    @endphp
                                <input type="datetime-local" class="form-control form-control-sm" name="start_time" value="{{ date("Y-m-d\TH:i", strtotime($occurrences[0]['start_time'].' UTC')) }}">
                                @else
                                <input type="datetime-local" class="form-control form-control-sm" name="start_time" value="{{ date("Y-m-d\TH:i", strtotime($meeting['start_time'].' UTC')) }}">
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Thời lượng</td>
                            <td>
                                <div class="input-group input-group-sm">
                                    @if ($meeting['type'] == 8)
                                    <input type="number" class="form-control" name="duration" placeholder="60" value="{{ $occurrences[0]['duration'] }}">
                                    @else
                                    <input type="number" class="form-control" name="duration" placeholder="60" value="{{ $meeting['duration'] }}">
                                    @endif
                                    <div class="input-group-append">
                                      <span class="input-group-text">phút</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                      <input type="checkbox" class="custom-control-input" id="recurrences" v-model="isRecurrence" name="recurrence">
                                      <label class="custom-control-label" for="recurrences">Lớp học định kỳ</label>
                                    </div>
                                </div>
                                <div id="info-recurrence" class="">
                                    <div class="row">
                                        <div class="col-sm-7">
                                            <div class="form-group">
                                                <label>Tái diễn</label>
                                                <select class="custom-select" name="recurrence_type" v-model="recurrenceType">
                                                  <option value="1">Hàng ngày</option>
                                                  <option value="2">Hàng tuần</option>
                                                  <option value="3">Hàng tháng</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label>Lặp lại mỗi</label>
                                                <div class="input-group">
                                                    @if ($meeting['type'] == 8)
                                                    <input type="number" min="1" class="form-control" value="{{ $recurrence['repeat_interval'] }}" name="recurrence_repeat_interval">
                                                    @else
                                                    <input type="number" min="1" class="form-control" value="1" name="recurrence_repeat_interval">
                                                    @endif
                                                    <div class="input-group-append">
                                                      <span class="input-group-text">@{{recurrenceTypeText}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-5" v-if="recurrenceType != 1">
                                            <div class="form-group">
                                                <label>Diễn ra vào lúc </label>
                                                <select v-if="recurrenceType == 2" multiple class="form-control" name="recurrence_weekly_days[]" v-model="recurrenceWeeklyDays">
                                                  <option value="1">Chủ nhật</option>
                                                  <option value="2">Thứ 2</option>
                                                  <option value="3">Thứ 3</option>
                                                  <option value="4">Thứ 4</option>
                                                  <option value="5">Thứ 5</option>
                                                  <option value="6">Thứ 6</option>
                                                  <option value="7">Thứ 7</option>
                                                </select>
                                                <span v-if="recurrenceType == 2">(Giữ Ctrl để chọn nhiều)</span>
                                                <div style="display: flex" v-if="recurrenceType == 3">
                                                    <span>Ngày</span>
                                                    <select class="form-control form-control-sm" name="recurrence_monthly_day" v-model="recurrenceMonthlyDay" style="width: 60px; margin-left: 10px; margin-right: 10px;">
                                                        <option v-for="item in 31" :value="item">@{{item}}</option>
                                                    </select>
                                                    <span>trong tháng</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="form-group">
                                                <label>Ngày kết thúc</label>
                                                <div class="form-check" style="margin-bottom: 10px;">
                                                  <input class="form-check-input" type="radio" value="datetime" id="recurrence-end-type-datetime" name="recurrence_end_type" v-model="recurrenceEndType">
                                                  <label class="form-check-label" for="recurrence-end-type-datetime" style="display: flex">Tới lúc <input type="date" class="form-control form-control-sm" name="recurrence_end_date_time" v-model="recurrenceEndDateTime" style="width: 150px; margin-left: 10px;"></label>
                                                </div>
                                                <div class="form-check">
                                                  <input class="form-check-input" type="radio" value="times" id="recurrence-end-type-times" name="recurrence_end_type" v-model="recurrenceEndType">
                                                  <label class="form-check-label" for="recurrence-end-type-times" style="display: flex">Sau <input type="number" class="form-control form-control-sm" name="recurrence_end_times" min="1" v-model="recurrenceEndTimes" style="width: 60px; margin-left: 10px; margin-right: 10px;"> buổi học</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Bảo mật</td>
                            <td>
                                <div class="input-group input-group-sm" style="width: 200px">
                                    <input class="form-control" name="password" placeholder="Mật khẩu" value="{{ $meeting['password'] }}" :type="showPassword ? 'text' : 'password'">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" @mousedown="showPassword = true" @mouseup="showPassword = false" @mouseout="showPassword = false" type="button">
                                            <span class="fas fa-eye"></span>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Liên kết mời</td>
                            <td>
                                <span style="cursor: pointer" data-toggle="tooltip" data-placement="top" :title="titleCopy" @click="copyStringToClipboard('{{ $meeting['join_url'] }}')">@{{ linkEscape }}</span>
                            </td>
                        </tr>
                    </table><br/>
                    <div class="text-center">
                        @if (!$meeting['is_start'])
                        <a class="btn btn-danger" href="{{ $meeting['start_url'] }}" target="_blank">Bắt đầu lớp học</a>
                        <button class="btn btn-outline-primary" type="submit">Cập nhật thông tin</button>
                        @else
                        <a class="btn btn-danger disabled" href="{{ $meeting['start_url'] }}" target="_blank">Lớp học đang diễn ra ...</a>
                        <button class="btn btn-outline-primary" type="submit">Cập nhật thông tin</button>
                        @endif
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
                    <input type="hidden" name="id_meeting" value="{{ $meeting['id'] }}">
                    <div id="js-invite-course">
                        <div class="card">
                            <div class="card-header">Học viên trong khoá học (Chọn khoá học để lấy học viên rồi gửi thông báo, không lưu lại)</div>
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
                                    <tr v-for="item in inviteUsers" :key="item.id">
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
                                                    <button type="button" class="btn btn-info btn-sm" @click="addItem(item)" :disabled="userIds.includes(item.id)"><i class="fas fa-plus"></i></button>
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
new Vue({
    el: '#js-edit-zoom',
    data: {
        showPassword: false,
        date: new Date(),
        isRecurrence: false,
        recurrenceType: 1,
        recurrenceTypeText: 'ngày',
        recurrenceEndType: '',
        recurrenceEndTimes: 7,
        recurrenceEndDateTime: undefined,
        recurrenceWeeklyDays: [],
        recurrenceMonthlyDay: undefined,
        linkEscape: '',
        titleCopy: 'Sao chép'
    },
    mounted() {
        $( "#info-recurrence" ).hide();
        this.recurrenceEndDateTime = moment(new Date(this.date.setMonth(this.date.getMonth()+6))).format('YYYY-MM-DD');
        const x = '{{ $meeting['join_url'] }}';
        if (x.length >= 30) {
            this.linkEscape = x.substring(0, 20) + "..." + x.substring(x.length - 20, x.length);
        } else {
            this.linkEscape = x;
        }
        @if($meeting['type'] == 8)
        this.isRecurrence = true;
        this.recurrenceType = {{ $recurrence['type'] }};
            @if(array_key_exists('end_times', $recurrence))
                this.recurrenceEndType = 'times';
                this.recurrenceEndTimes = {{$recurrence['end_times']}};
            @endif
            @if(array_key_exists('end_date_time', $recurrence))
                this.recurrenceEndType = 'datetime';
                this.recurrenceEndDateTime = '{{$recurrence['end_date_time']}}'.split('T')[0];
            @endif
            @if(array_key_exists('weekly_days', $recurrence))
                this.recurrenceWeeklyDays = '{{$recurrence['weekly_days']}}'.split(',');
            @endif
            @if(array_key_exists('monthly_day', $recurrence))
                this.recurrenceMonthlyDay = {{$recurrence['monthly_day']}};
            @endif
        @endif
    },
    methods: {
        copyStringToClipboard (str) {
            // Create new element
            var el = document.createElement('textarea');
            // Set value (string to be copied)
            el.value = str;
            // Set non-editable to avoid focus and move outside of view
            el.setAttribute('readonly', '');
            el.style = {position: 'absolute', left: '-9999px'};
            document.body.appendChild(el);
            // Select text inside element
            el.select();
            // Copy text to clipboard
            document.execCommand('copy');
            // Remove temporary element
            document.body.removeChild(el);
            this.titleCopy = 'Đã sao chép';
            // const Toast = Swal.mixin({
            //     toast: true,
            //     position: 'top-end',
            //     showConfirmButton: false,
            //     timer: 3000
            // });
            // Toast.fire({
            //     type: 'success',
            //     title: 'Đã sao chép đường dẫn.'
            // })
        }
    },
    watch: {
        isRecurrence() {
            $( "#info-recurrence" ).slideToggle();
        },
        recurrenceType() {
            if (this.recurrenceType == 1) {
                this.recurrenceTypeText = 'ngày';
            } else if (this.recurrenceType == 2) {
                this.recurrenceTypeText = 'tuần';
            } else if (this.recurrenceType == 3) {
                this.recurrenceTypeText = 'tháng';
            }
        }
    }
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
            userIds: [],
            inviteUsers: [],
            searchTimer: undefined,
            searchResult: [],
            keyword: undefined,
            isSearching: false,
        },
        mounted() {
            axios.get('{{ route("admin.zoom.get_user_meeting_zoom", ['id' => $meeting['id']]) }}').then(res => {
                res.forEach(item => {
                    this.inviteUsers.push(item.user);
                    this.userIds.push(item.user.id);
                });
            });
        },
        methods: {
            showAddItemModal() {
                $('#js-invite-user-modal').modal('show');
            },
            addItem(item) {
                this.inviteUsers.push(item);
                this.userIds.push(item.id);
            },
            deleteItem(id) {
                const index = this.inviteUsers.findIndex(x => x.id == id);
                this.inviteUsers.splice(index, 1);
                this.userIds.splice(index, 1);
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
