@extends('backend.master')

@section('title')
@if(request()->routeIs('admin.post.add'))
    Thêm bài viết mới
@else
    Sửa bài viết
@endif
@endsection

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">
            @if(request()->routeIs('admin.post.add'))
                Thêm bài viết mới
            @else
                Sửa bài viết
            @endif
        </h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.post.list') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
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
                        <option value="{{ $category->id }}" {{ ($data->category_id ?? old('category_id')) == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Tiêu đề</label>
                <input type="text" name="title" placeholder="Tiêu đề" value="{{ $data->title ?? old('title') }}" class="form-control @error('title') is-invalid @enderror">
                @error('title')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Đường dẫn</label>
                <small><i class="fas fa-question-circle text-warning" data-toggle="popover" data-html="true" data-content="- Hạn chế thay đổi vì ảnh hưởng tới SEO.<br>- Nếu bỏ trống, hệ thống tự tạo đường dẫn theo tiêu đề."></i></small>
                <input type="text" name="slug" placeholder="Đường dẫn" value="{{ $data->slug ?? old('slug') }}" class="form-control @error('slug') is-invalid @enderror">
                @error('slug')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Ảnh thu nhỏ (thumbnail)</label>
                        <div class="input-group">
                            <input type="text" name="thumbnail" placeholder="Ảnh thu nhỏ" value="{{ $data->thumbnail ?? old('thumbnail') }}" class="form-control @error('thumbnail') is-invalid @enderror" id="ck-thumbnail">
                            @error('thumbnail')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                            <div class="input-group-append">
                                <button type="button" class="input-group-text" onclick="selectFileWithCKFinder('ck-thumbnail', 'ck-thumbnail-preview')">Chọn file</button>
                            </div>
                        </div>
                        <img class="image-preview" src="{{ $data->thumbnail ?? old('thumbnail') }}" id="ck-thumbnail-preview">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Ảnh bìa (cover)</label>
                        <div class="input-group">
                            <input type="text" name="cover" placeholder="Ảnh bìa" value="{{ $data->cover ?? old('cover') }}" class="form-control @error('cover') is-invalid @enderror" id="ck-cover">
                            @error('cover')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                            <div class="input-group-append">
                                <button type="button" class="input-group-text" onclick="selectFileWithCKFinder('ck-cover', 'ck-cover-preview')">Chọn file</button>
                            </div>
                        </div>
                        <img class="image-preview" src="{{ $data->cover ?? old('cover') }}" id="ck-cover-preview">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Mô tả nội dung</label>
                <textarea name="description" rows="3" placeholder="Mô tả nội dung" class="form-control @error('description') is-invalid @enderror">{{ $data->description ?? old('description') }}</textarea>
                @error('description')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Nội dung bài viết</label>
                <textarea name="content" class="editor">{!! $data->content ?? old('content') !!}</textarea>
            </div>
            <div class="form-group">
                <label>Hiển thị bài viết</label>
                <?php $enabled = $data->enabled ?? old('enabled') ?? true; ?>
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
                <label>Vị trí hiển thị</label>
                <div>
                    @foreach(get_template_position(\App\Constants\ObjectType::POST) as $item)
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
                <label>Bài viết liên quan</label>
                <div id="js-related-post">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped table-borderless">
                                <tr v-for="item in relatedPosts" :key="item.id">
                                    <td>
                                        @{{ item.id }}
                                        <input type="hidden" name="__related_posts[]" :value="item.id">
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
                    <div class="modal fade" tabindex="-1" id="js-related-post-modal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Chọn bài viết liên quan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Tìm kiếm bài viết" v-model="keyword">
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
                @error('__related_posts')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <hr>
            <div class="form-group">
                <label>Meta Title</label>
                <small><i class="fas fa-question-circle text-warning" data-toggle="popover" data-html="true" data-content="- Tiêu đề hiển thị trên các công cụ tìm kiếm.<br>- Nếu bỏ trống, hệ thống tự lấy theo tiêu đề."></i></small>
                <input type="text" name="meta_title" placeholder="Meta Title" value="{{ $data->meta_title ?? old('meta_title') }}" class="form-control @error('meta_title') is-invalid @enderror">
                @error('meta_title')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Meta description</label>
                <small><i class="fas fa-question-circle text-warning" data-toggle="popover" data-html="true" data-content="- Mô tả hiển thị trên các công cụ tìm kiếm.<br>- Nếu bỏ trống, hệ thống tự lấy theo mô tả."></i></small>
                <textarea name="meta_description" rows="3" placeholder="Meta description" class="form-control @error('meta_description') is-invalid @enderror">{{ $data->meta_description ?? old('meta_description') }}</textarea>
                @error('meta_description')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>OG Title</label>
                <small><i class="fas fa-question-circle text-warning" data-toggle="popover" data-html="true" data-content="- Tiêu đề hiển thị trên các trang mạng xã hội.<br>- Nếu bỏ trống, hệ thống tự lấy theo tiêu đề."></i></small>
                <input type="text" name="og_title" placeholder="OG Title" value="{{ $data->og_title ?? old('og_title') }}" class="form-control @error('og_title') is-invalid @enderror">
                @error('og_title')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>OG description</label>
                <small><i class="fas fa-question-circle text-warning" data-toggle="popover" data-html="true" data-content="- Mô tả hiển thị trên các trang mạng xã hội.<br>- Nếu bỏ trống, hệ thống tự lấy theo mô tả."></i></small>
                <textarea name="og_description" rows="3" placeholder="OG description" class="form-control @error('og_description') is-invalid @enderror">{{ $data->og_description ?? old('og_description') }}</textarea>
                @error('og_description')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>OG Image</label>
                        <small><i class="fas fa-question-circle text-warning" data-toggle="popover" data-html="true" data-content="- Hình ảnh hiển thị trên các trang mạng xã hội.<br>- Nếu bỏ trống, hệ thống tự lấy theo ảnh bìa."></i></small>
                        <div class="input-group">
                            <input type="text" name="og_image" placeholder="OG Image" value="{{ $data->og_image ?? old('og_image') }}" class="form-control @error('og_image') is-invalid @enderror" id="ck-og-image">
                            @error('og_image')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                            <div class="input-group-append">
                                <button type="button" class="input-group-text" onclick="selectFileWithCKFinder('ck-og-image', 'ck-og-image-preview')">Chọn file</button>
                            </div>
                        </div>
                        <img class="image-preview" src="{{ $data->og_image ?? old('og_image') }}" id="ck-og-image-preview">
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
    const POST_ID = {{ $data->id ?? 'undefined' }};

    new Vue({
        el: '#js-related-post',
        data: {
            relatedPosts: [],
            searchTimer: undefined,
            searchResult: [],
            keyword: undefined,
            isSearching: false,
        },
        mounted() {
            axios.get('{{ route("admin.post.get_related_post") }}', {
                params: {
                    id: POST_ID
                }
            }).then(res => {
                this.relatedPosts = res.data;
            });
        },
        methods: {
            showAddItemModal() {
                $('#js-related-post-modal').modal('show');
            },
            addItem(item) {
                this.relatedPosts.push(item);
            },
            deleteItem(id) {
                const index = this.relatedPosts.findIndex(x => x.id == id);
                this.relatedPosts.splice(index, 1);
            },
        },
        watch: {
            keyword(newVal, oldVal) {
                this.isSearching = true;
                clearTimeout(this.searchTimer);
                this.searchTimer = setTimeout(() => {
                    axios.get('{{ route("admin.post.search_post") }}', {
                        params: {
                            id: POST_ID,
                            keyword: this.keyword
                        }
                    }).then(res => {
                        this.searchResult = res.data;
                    }).finally(() => {
                        this.isSearching = false;
                    });
                }, 1000);
            }
        }
    });

    new Vue({
        el: '#js-related-course',
        data: {
            relatedCourses: [],
            searchTimer: undefined,
            searchResult: [],
            keyword: undefined,
            isSearching: false,
        },
        mounted() {
            axios.get('{{ route("admin.post.get_related_course") }}', {
                params: {
                    id: POST_ID
                }
            }).then(res => {
                this.relatedCourses = res.data;
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
                            id: POST_ID,
                            keyword: this.keyword
                        }
                    }).then(res => {
                        this.searchResult = res.data;
                    }).finally(() => {
                        this.isSearching = false;
                    });
                }, 1000);
            }
        }
    });

</script>
@endsection
