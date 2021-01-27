@extends('backend.master')

@section('title')
@if(request()->routeIs('admin.user.add'))
    Thêm thành viên mới
@else
    Sửa thông tin thành viên : {{ $data->username }}
@endif
@endsection

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">
            @if(request()->routeIs('admin.user.add'))
                Thêm thành viên mới
            @else
                Sửa thông tin thành viên : {{ $data->username }}
            @endif
        </h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.user.list') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
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
    <form action="" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Username" value="{{ $data->username ?? old('username') }}" class="form-control @error('username') is-invalid @enderror">
                @error('username')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Email" value="{{ $data->email ?? old('email') }}" class="form-control @error('email') is-invalid @enderror">
                @error('email')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" placeholder="Your name" value="{{ $data->name ?? old('name') }}" class="form-control">
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="input-group @error('password') is-invalid @enderror">
                    <input type="text" class="form-control" name="password" placeholder="Password" id="js-password-input">
                    <div class="input-group-append">
                        <button class="btn btn-success" type="button" id="js-generate-password"><i class="fas fa-key"></i> Generate</button>
                    </div>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Quyền hạn</label>
                <select name="role_id" class="form-control">
                    <option value="">Học viên</option>
                    @foreach($roles as $item)
                        <option value="{{ $item->id }}" {{ $data->hasRole($item) ? 'selected' : '' }}>{{ $item->name }}</option>
                    @endforeach
                </select>
                @error('role_id')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Số tiền trong tài khoản</label>
                <input type="text" name="money" placeholder="Số tiền trong tài khoản" value="{{ $data->money ?? old('money') }}" class="form-control currency @error('enabled') is-invalid @enderror">
                @error('money')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Ảnh đại diện</label>
                        <div class="input-group">
                            <input type="text" name="avatar" placeholder="Ảnh đại diện" value="{{ $data->avatar ?? old('avatar') }}" class="form-control @error('avatar') is-invalid @enderror" id="ck-avatar">
                            <div class="input-group-append">
                                <button type="button" class="input-group-text" onclick="selectFileWithCKFinder('ck-avatar', 'ck-avatar-preview')">Chọn file</button>
                            </div>
                        </div>
                        @error('avatar')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                        <img class="image-preview" src="{{ $data->avatar ?? old('avatar') }}" id="ck-avatar-preview">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Khóa học đã sở hữu</label>
                <div id="js-user-courses">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped table-borderless">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Ảnh đại diện</th>
                                        <th>Tiêu đề</th>
                                        <th>Thời gian mua</th>
                                        <th>
                                            Hết hạn lúc
                                            <small><i class="fas fa-question-circle text-warning" data-toggle="popover" data-html="true" data-content="Bỏ trống nếu học viên sở hữu khóa học vĩnh viễn."></i></small>
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in userCourses" :key="item.id">
                                        <td>
                                            @{{ item.course.id }}
                                            <input type="hidden" :name="'__user_courses[' + index + '][course_id]'" :value="item.course.id">
                                        </td>
                                        <td>
                                            <img :src="item.course.thumbnail" class="img-thumbnail">
                                        </td>
                                        <td>@{{ item.course.title }}</td>
                                        <td>
                                            @{{ item.created_at }}
                                            <input type="hidden" :name="'__user_courses[' + index + '][created_at]'" :value="item.created_at">
                                        </td>
                                        <td>
                                            <div>
                                                <datetimepicker v-model="item.expires_on" format="YYYY-MM-DD hh:mm:ss" formatted="YYYY-MM-DD hh:mm:ss" :no-label="true" />
                                            </div>
                                            <input type="hidden" :name="'__user_courses[' + index + '][expires_on]'" :value="item.expires_on">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm" @click="deleteCourse(item.course.id)"><i class="far fa-trash-alt"></i> Xóa</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr>
                            <div class="text-center">
                                <button type="button" class="btn btn-info" @click="showAddCourseModal"><i class="fas fa-plus"></i> Thêm</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" tabindex="-1" id="js-user-courses-modal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Chọn khóa học liên quan</h5>
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
                                        <tr v-for="course in searchResult" :key="course.id">
                                            <td>@{{ course.id }}</td>
                                            <td>
                                                <img :src="course.thumbnail" class="img-thumbnail">
                                            </td>
                                            <td>@{{ course.title }}</td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm" @click="addCourse(course)"><i class="fas fa-plus"></i></button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @error('__user_courses')
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
    $('#js-generate-password').click(function () {
        var password = '';
        var passwordLength = 10;
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < passwordLength; i++) {
            password += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        $('#js-password-input').val(password);

        $('#js-password-input').select();
        document.execCommand('copy');
    });

    new Vue({
        el: '#js-user-courses',
        data: {
            userCourses: [],
            searchTimer: undefined,
            searchResult: [],
            keyword: undefined,
            isSearching: false,
        },
        mounted() {
            axios.get('{{ route("admin.user.get_user_courses", [ "id" => $data->id ?? 0 ]) }}').then(res => {
                this.userCourses = res;
            });
        },
        methods: {
            showAddCourseModal() {
                $('#js-user-courses-modal').modal('show');
            },
            addCourse(course) {
                const exists = this.userCourses.find(x => x.course.id == course.id) !== undefined;
                if (exists) {
                    alert('Học viên đã sở hữu khóa học này rồi.');
                    return;
                }
                this.userCourses.push({
                    created_at: moment().format('YYYY-MM-DD hh:mm:ss'),
                    expires_on: course.buyer_days_owned ? moment().add(course.buyer_days_owned, 'days').format('YYYY-MM-DD hh:mm:ss') : null,
                    course: course
                });
            },
            deleteCourse(courseId) {
                const index = this.userCourses.findIndex(x => x.course.id == courseId);
                this.userCourses.splice(index, 1);
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
@endsection
