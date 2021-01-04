@extends('backend.master')

@section('title')
@if(request()->routeIs('admin.promo.add'))
    Thêm mã khuyến mãi mới
@else
    Sửa mã khuyến mãi
@endif
@endsection

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">
            @if(request()->routeIs('admin.promo.add'))
                Thêm mã khuyến mãi mới
            @else
                Sửa mã khuyến mãi
            @endif
        </h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.promo.list') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
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

<div class="card" id="promo">
    <div class="card-body">
        <div class="form-group">
            <label>Mã khuyến mãi</label>
            <input type="text" v-model="promo.code" placeholder="Mã khuyến mãi" class="form-control @error('promo.code') is-invalid @enderror">
            @error('promo.code')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group">
            <label>Loại</label>
            <div>
                <div class="form-check-inline">
                    <input type="radio" v-model="promo.type" class="form-check-input @error('promo.type') is-invalid @enderror" id="cr-type-1" value="{{ \App\Constants\PromoType::DISCOUNT }}">
                    <label class="form-check-label" for="cr-type-1">Giảm giá</label>
                </div>
                <div class="form-check-inline">
                    <input type="radio" v-model="promo.type" class="form-check-input @error('promo.type') is-invalid @enderror" id="cr-type-2" value="{{ \App\Constants\PromoType::SAME_PRICE }}">
                    <label class="form-check-label" for="cr-type-2">Đồng giá</label>
                </div>
            </div>
        </div>
        <div v-if="promo.type == '{{ \App\Constants\PromoType::DISCOUNT }}'" class="form-group">
            <label>Phần trăm giảm giá</label>
            <div>
                <currency-input v-model="promo.value" class="form-control" placeholder="Phần trăm giảm giá" />
            </div>
            @error('promo.value')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div v-if="promo.type == '{{ \App\Constants\PromoType::SAME_PRICE }}'" class="form-group">
            <label>Đồng giá</label>
            <div>
                <currency-input v-model="promo.value" class="form-control" placeholder="Đồng giá" />
            </div>
            @error('promo.value')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group">
            <label>Chọn khóa học có trong combo</label>
            <small><i class="fas fa-question-circle text-warning" data-toggle="popover" data-html="true" data-content="- Khuyến mãi chỉ áp dụng khi người dùng mua đúng những khóa học có trong danh sách.<br>- Bỏ trống để áp dụng với tất cả các khóa học."></i></small>
            <div class="form-group">
                <div id="js-related-course">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped table-borderless">
                                <tr v-for="item in comboCourses" :key="item.id">
                                    <td>
                                        @{{ item.id }}
                                    </td>
                                    <td>
                                        <img :src="item.thumbnail" class="img-thumbnail">
                                    </td>
                                    <td>@{{ item.title }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" @click="deleteCourse(item.id)"><i class="far fa-trash-alt"></i> Xóa</button>
                                    </td>
                                </tr>
                            </table>
                            <hr>
                            <div class="text-center">
                                <button type="button" class="btn btn-info" @click="showAddCourseModal"><i class="fas fa-plus"></i> Thêm</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" tabindex="-1" id="js-related-course-modal">
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
                                                <button type="button" class="btn btn-info btn-sm" @click="addCourse(item)"><i class="fas fa-plus"></i></button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @error('combo_courses')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            @error('promo.value')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group">
            <label>Thời gian kết thúc</label>
            <div>
                <datetimepicker v-model="promo.expires_on" format="YYYY-MM-DD hh:mm" formatted="YYYY-MM-DD hh:mm" :no-label="true" />
            </div>
            @error('promo.expires_on')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group">
            <label>Loại</label>
            <div>
                <div class="form-check-inline">
                    <input type="radio" v-model="promo.used_many_times" class="form-check-input @error('promo.used_many_times') is-invalid @enderror" id="cr-type-many-users-1" :value="true">
                    <label class="form-check-label" for="cr-type-many-users-1">Dùng nhiều lần</label>
                </div>
                <div class="form-check-inline">
                    <input type="radio" v-model="promo.used_many_times" class="form-check-input @error('promo.used_many_times') is-invalid @enderror" id="cr-type-many-users-2" :value="false">
                    <label class="form-check-label" for="cr-type-many-users-2">Dùng một lần</label>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="button" class="btn btn-primary" @click="submit"><i class="fas fa-save"></i> Lưu</button>
    </div>
</div>
@endsection

@section('script')
<script>
    new Vue({
        el: '#promo',
        data: {
            promo: {
                code: undefined,
                type: '{{ \App\Constants\PromoType::DISCOUNT }}',
                value: undefined,
                combo_courses: [],
                expires_on: undefined,
                used_many_times: true
            },
            comboCourses: [],
            searchTimer: undefined,
            searchResult: [],
            keyword: undefined,
            isSearching: false,
        },
        mounted() {
            this.getItem();
        },
        methods: {
            submit() {
                this.promo.combo_courses = _.map(this.comboCourses, 'id');
                redirectPost(location.href, {
                    promo: this.promo
                });
            },
            getItem() {
                axios.get("{{ route('admin.promo.get_item', [ 'id' => $promo->id ?? 0 ]) }}").then(res => {
                    if (!res) return;
                    this.promo = res.promo;
                    this.comboCourses = res.combo_courses;
                });
            },
            showAddCourseModal() {
                $('#js-related-course-modal').modal('show');
            },
            addCourse(item) {
                this.comboCourses.push(item);
            },
            deleteCourse(id) {
                const index = this.comboCourses.findIndex(x => x.id == id);
                this.comboCourses.splice(index, 1);
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
