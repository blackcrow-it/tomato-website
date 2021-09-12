@extends('backend.master')

@section('title')
@if(request()->routeIs('admin.course.add'))
    Thêm khóa học mới
@else
    Sửa khóa học
@endif
@endsection

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">
            @if(request()->routeIs('admin.course.add'))
                Thêm khóa học mới
            @else
                Sửa khóa học
            @endif
        </h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.course.list') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
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
                <label>Chọn danh mục</label>
                <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                    <option value="">Không phân loại</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (old('category_id') ?? $data->category_id ?? null) == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Tiêu đề</label>
                <input type="text" name="title" placeholder="Tiêu đề" value="{{ old('title') ?? $data->title ?? null }}" class="form-control @error('title') is-invalid @enderror">
                @error('title')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Đường dẫn</label>
                <small><i class="fas fa-question-circle text-warning" data-toggle="popover" data-html="true" data-content="- Hạn chế thay đổi vì ảnh hưởng tới SEO.<br>- Nếu bỏ trống, hệ thống tự tạo đường dẫn theo tiêu đề."></i></small>
                <input type="text" name="slug" placeholder="Đường dẫn" value="{{ old('slug') ?? $data->slug ?? null }}" class="form-control @error('slug') is-invalid @enderror">
                @error('slug')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Ảnh thu nhỏ (thumbnail)</label>
                        <div class="input-group">
                            <input type="text" name="thumbnail" placeholder="Ảnh thu nhỏ" value="{{ old('thumbnail') ?? $data->thumbnail ?? null }}" class="form-control @error('thumbnail') is-invalid @enderror" id="ck-thumbnail">
                            @error('thumbnail')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                            <div class="input-group-append">
                                <button type="button" class="input-group-text" onclick="selectFileWithCKFinder('ck-thumbnail', 'ck-thumbnail-preview')">Chọn file</button>
                            </div>
                        </div>
                        <img class="image-preview" src="{{ old('thumbnail') ?? $data->thumbnail ?? null }}" id="ck-thumbnail-preview">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Ảnh bìa (cover)</label>
                        <div class="input-group">
                            <input type="text" name="cover" placeholder="Ảnh bìa" value="{{ old('cover') ?? $data->cover ?? null }}" class="form-control @error('cover') is-invalid @enderror" id="ck-cover">
                            @error('cover')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                            <div class="input-group-append">
                                <button type="button" class="input-group-text" onclick="selectFileWithCKFinder('ck-cover', 'ck-cover-preview')">Chọn file</button>
                            </div>
                        </div>
                        <img class="image-preview" src="{{ old('cover') ?? $data->cover ?? null }}" id="ck-cover-preview">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Intro Youtube ID</label>
                <input type="text" name="intro_youtube_id" placeholder="Intro Youtube ID" value="{{ old('intro_youtube_id') ?? $data->intro_youtube_id ?? null }}" class="form-control @error('intro_youtube_id') is-invalid @enderror">
                @error('intro_youtube_id')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Mô tả nội dung</label>
                <textarea name="description" class="editor">{!! old('description') ?? $data->description ?? null !!}</textarea>
            </div>
            <div class="form-group">
                <label>Nội dung khóa học</label>
                <textarea name="content" class="editor">{!! old('content') ?? $data->content ?? null !!}</textarea>
            </div>
            <div class="form-group">
                <label>Ghi chú cho người đã mua khóa học (Hiển thị dưới video)</label>
                <textarea name="video_footer_text" class="editor">{!! old('video_footer_text') ?? $data->video_footer_text ?? null !!}</textarea>
            </div>
            <div class="form-group">
                <label>Giảng viên</label>
                <select name="teacher_id" class="form-control">
                    <option value="">Chọn giảng viên</option>
                    @foreach ($teachers as $item)
                        <option value="{{ $item->id }}" {{ (old('teacher_id') ?? $data->teacher_id ?? null) == $item->id ? 'selected' : null }}>{{ $item->name }}</option>
                    @endforeach
                </select>
                @error('teacher_id')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Trình độ</label>
                <select name="level" class="form-control @error('level') is-invalid @enderror">
                    <option value="">Không phân loại</option>
                    <option value="{{ \App\Constants\CourseLevel::ELEMENTARY }}" {{ (old('level') ?? $data->level ?? null) == App\Constants\CourseLevel::ELEMENTARY ? 'selected' : '' }}>Sơ cấp</option>
                    <option value="{{ \App\Constants\CourseLevel::INTERMEDIATE }}" {{ (old('level') ?? $data->level ?? null) == App\Constants\CourseLevel::INTERMEDIATE ? 'selected' : '' }}>Trung cấp</option>
                    <option value="{{ \App\Constants\CourseLevel::ADVANCED }}" {{ (old('level') ?? $data->level ?? null) == App\Constants\CourseLevel::ADVANCED ? 'selected' : '' }}>Cao cấp</option>
                </select>
                @error('level')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Giá tiền</label>
                <input type="text" name="price" placeholder="Giá tiền" value="{{ old('price') ?? $data->price ?? null }}" class="form-control currency @error('price') is-invalid @enderror">
                @error('price')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Giá gốc</label>
                <input type="text" name="original_price" placeholder="Giá gốc" value="{{ old('original_price') ?? $data->original_price ?? null }}" class="form-control currency @error('original_price') is-invalid @enderror">
                @error('original_price')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Số ngày sở hữu sau khi mua</label>
                <small><i class="fas fa-question-circle text-warning" data-toggle="popover" data-html="true" data-content="Bỏ trống để học viên có thể sở hữu mãi mãi."></i></small>
                <input type="text" name="buyer_days_owned" placeholder="Số ngày sở hữu sau khi mua" value="{{ old('buyer_days_owned') ?? $data->buyer_days_owned ?? null }}" class="form-control currency @error('buyer_days_owned') is-invalid @enderror">
                @error('buyer_days_owned')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Hiển thị khóa học</label>
                <?php $enabled = old('enabled') ?? $data->enabled ?? null ?? true; ?>
                <div>
                    <div class="form-check-inline">
                        <input class="form-check-input @error('enabled') is-invalid @enderror" type="radio" id="cr-enabled-1" name="enabled" value="1" {{ $enabled == true ? 'checked' : '' }}>
                        <label class="form-check-label" for="cr-enabled-1">Hiển thị</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input @error('enabled') is-invalid @enderror" type="radio" id="cr-enabled-0" name="enabled" value="0" {{ $enabled == false ? 'checked' : '' }}>
                        <label class="form-check-label" for="cr-enabled-0">Ẩn</label>
                    </div>
                </div>
                @error('enabled')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Vị trí hiển thị</label>Meta Title
                <div>
                    @foreach(get_template_position(\App\Constants\ObjectType::COURSE) as $item)
                        <div class="form-check">
                            <input class="form-check-input @error('__template_position') is-invalid @enderror" type="checkbox" id="cr-template-position-{{ $loop->index }}" name="__template_position[]" value="{{ $item['code'] }}" {{ in_array($item['code'], $data->__template_position ?? []) ? 'checked' : '' }}>
                            <label class="form-check-label" for="cr-template-position-{{ $loop->index }}">{{ $item['name'] }}</label>
                        </div>
                    @endforeach
                </div>
                @error('__template_position')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Khóa học liên quan</label>
                <div id="js-related-course">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped table-borderless">
                                <tr v-for="item in relatedCourses" :key="item.id">
                                    <td>
                                        @{{ item.id }}
                                        <input type="hidden" name="__related_courses[]" :value="item.id">
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
                    <div class="modal fade" tabindex="-1" id="js-related-course-modal">
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
                                        <tr v-for="item in searchResult" :key="item.id">
                                            <td>@{{ item.id }}</td>
                                            <td>
                                                <img :src="item.thumbnail" class="img-thumbnail">
                                            </td>
                                            <td>@{{ item.title }}</td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm" @click="addItem(item)"><i class="fas fa-plus"></i></button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @error('__related_courses')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Combo chứa khoá học này</label>
                <div id="js-related-combos-course">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped table-borderless">
                                <tr v-for="item in relatedCombosCourse" :key="item.id">
                                    <td>
                                        @{{ item.id }}
                                        <input type="hidden" name="__related_combos_course[]" :value="item.id">
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
                    <div class="modal fade" tabindex="-1" id="js-related-combos-course-modal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Chọn combo khoá học</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Tìm kiếm combo khóa học" v-model="keyword">
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
                                                <button type="button" class="btn btn-info btn-sm" @click="addItem(item)" :disabled="comboCourseIds.includes(item.id)"><i class="fas fa-plus"></i></button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @error('__related_combos_course')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Tài liệu liên quan</label>
                <div id="js-related-book">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped table-borderless">
                                <tr v-for="item in relatedBooks" :key="item.id">
                                    <td>
                                        @{{ item.id }}
                                        <input type="hidden" name="__related_books[]" :value="item.id">
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
                    <div class="modal fade" tabindex="-1" id="js-related-book-modal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Chọn tài liệu liên quan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Tìm kiếm sách" v-model="keyword">
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
                                                <button type="button" class="btn btn-info btn-sm" @click="addItem(item)"><i class="fas fa-plus"></i></button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @error('__related_books')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <hr>
            <div class="form-group">
                <label>Ngày tạo</label>
                <small><i class="fas fa-question-circle text-warning" data-toggle="popover" data-html="true" data-content="- Ngày tạo khóa học"></i></small>
                <input type="text" name="meta_title" placeholder="ngày tạo" value="{{ old('created_at') ?? $data->created_at ?? null }}" class="form-control" readonly>
            </div>
            <div id="js-meta-data">
                <div class="form-group">
                    <label>Meta Title</label>
                    <small><i class="fas fa-question-circle text-warning" data-toggle="popover" data-html="true" data-content="- Tiêu đề hiển thị trên các công cụ tìm kiếm.<br>- Nếu bỏ trống, hệ thống tự lấy theo tiêu đề."></i></small>
                    <span> @{{ metaTitle.length }}/60</span>
                    <input type="text" name="meta_title" maxlength="60" placeholder="Meta Title" v-model="metaTitle" class="form-control @error('meta_title') is-invalid @enderror">
                    @error('meta_title')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Meta description</label>
                    <small><i class="fas fa-question-circle text-warning" data-toggle="popover" data-html="true" data-content="- Mô tả hiển thị trên các công cụ tìm kiếm.<br>- Nếu bỏ trống, hệ thống tự lấy theo mô tả."></i></small>
                    <span> @{{ metaDesc.length }}/155</span>
                    <textarea name="meta_description" rows="3" maxlength="155" v-model="metaDesc" placeholder="Meta description" class="form-control @error('meta_description') is-invalid @enderror"></textarea>
                    @error('meta_description')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>OG Title</label>
                    <small><i class="fas fa-question-circle text-warning" data-toggle="popover" data-html="true" data-content="- Tiêu đề hiển thị trên các trang mạng xã hội.<br>- Nếu bỏ trống, hệ thống tự lấy theo tiêu đề."></i></small>
                    <span> @{{ ogTitle.length }}/95</span>
                    <input type="text" name="og_title" placeholder="OG Title" maxlength="95" v-model="ogTitle" class="form-control @error('og_title') is-invalid @enderror">
                    @error('og_title')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>OG description</label>
                    <small><i class="fas fa-question-circle text-warning" data-toggle="popover" data-html="true" data-content="- Mô tả hiển thị trên các trang mạng xã hội.<br>- Nếu bỏ trống, hệ thống tự lấy theo mô tả."></i></small>
                    <span> @{{ ogDesc.length }}/200</span>
                    <textarea name="og_description" rows="3" placeholder="OG description" maxlength="200" v-model="ogDesc" class="form-control @error('og_description') is-invalid @enderror"></textarea>
                    @error('og_description')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>OG Image</label>
                            <small><i class="fas fa-question-circle text-warning" data-toggle="popover" data-html="true" data-content="- Hình ảnh hiển thị trên các trang mạng xã hội.<br>- Nếu bỏ trống, hệ thống tự lấy theo ảnh bìa."></i></small>
                            <div class="input-group">
                                <input type="text" name="og_image" placeholder="OG Image" value="{{ old('og_image') ?? $data->og_image ?? null }}" class="form-control @error('og_image') is-invalid @enderror" id="ck-og-image">
                                @error('og_image')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                                <div class="input-group-append">
                                    <button type="button" class="input-group-text" onclick="selectFileWithCKFinder('ck-og-image', 'ck-og-image-preview')">Chọn file</button>
                                </div>
                            </div>
                            <img class="image-preview" src="{{ old('og_image') ?? $data->og_image ?? null }}" id="ck-og-image-preview">
                        </div>
                    </div>
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
    const COURSE_ID = "{{ $data->id ?? 'undefined' }}";

    new Vue({
        el: '#js-related-course',
        data: {
            relatedCourses: [],
            searchTimer: undefined,
            searchResult: [],
            keyword: undefined,
            isSearching: false,
            metaTitle: '{{ old('meta_title') ?? $data->meta_title ?? null }}'
        },
        mounted() {
            axios.get('{{ route("admin.course.get_related_course") }}', {
                params: {
                    id: COURSE_ID
                }
            }).then(res => {
                this.relatedCourses = res;
            });
        },
        methods: {
            showAddItemModal() {
                $('#js-related-course-modal').modal('show');
            },
            addItem(item) {
                this.relatedCourses.push(item);
            },
            deleteItem(id) {
                const index = this.relatedCourses.findIndex(x => x.id == id);
                this.relatedCourses.splice(index, 1);
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

    new Vue({
        el: '#js-related-book',
        data: {
            relatedBooks: [],
            searchTimer: undefined,
            searchResult: [],
            keyword: undefined,
            isSearching: false,
        },
        mounted() {
            axios.get('{{ route("admin.course.get_related_book") }}', {
                params: {
                    id: COURSE_ID
                }
            }).then(res => {
                this.relatedBooks = res;
            });
        },
        methods: {
            showAddItemModal() {
                $('#js-related-book-modal').modal('show');
            },
            addItem(item) {
                this.relatedBooks.push(item);
            },
            deleteItem(id) {
                const index = this.relatedBooks.findIndex(x => x.id == id);
                this.relatedBooks.splice(index, 1);
            },
        },
        watch: {
            keyword(newVal, oldVal) {
                this.isSearching = true;
                clearTimeout(this.searchTimer);
                this.searchTimer = setTimeout(() => {
                    axios.get('{{ route("admin.book.search_book") }}', {
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

    new Vue({
        el: '#js-related-combos-course',
        data: {
            comboCourseIds: [],
            relatedCombosCourse: [],
            searchTimer: undefined,
            searchResult: [],
            keyword: undefined,
            isSearching: false,
        },
        mounted() {
            axios.get('{{ route("admin.course.get_related_combos_course") }}', {
                params: {
                    id: COURSE_ID
                }
            }).then(res => {
                res.forEach(item => {
                    this.relatedCombosCourse.push(item);
                    this.comboCourseIds.push(item.id);
                });
            });
        },
        methods: {
            showAddItemModal() {
                $('#js-related-combos-course-modal').modal('show');
            },
            addItem(item) {
                this.relatedCombosCourse.push(item);
                this.comboCourseIds.push(item.id);
            },
            deleteItem(id) {
                const index = this.relatedCombosCourse.findIndex(x => x.id == id);
                this.relatedCombosCourse.splice(index, 1);
                this.comboCourseIds.splice(index, 1);
            },
        },
        watch: {
            keyword(newVal, oldVal) {
                this.isSearching = true;
                clearTimeout(this.searchTimer);
                this.searchTimer = setTimeout(() => {
                    axios.get('{{ route("admin.combo_courses.search") }}', {
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

    new Vue({
        el: '#js-meta-data',
        data: {
            metaTitle: "{!! $data->meta_title ?? old('meta_title') !!}",
            metaDesc: "{!! $data->meta_description ?? old('meta_description') !!}",
            ogTitle: "{!! $data->og_title ?? old('og_title') !!}",
            ogDesc: "{!! $data->og_description ?? old('og_description') !!}",
        },
    });

</script>
@endsection
