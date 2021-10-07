@extends('backend.master')

@section('title')
@if(request()->routeIs('admin.category.add'))
    Thêm danh mục mới
@else
    Sửa danh mục
@endif
@endsection

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">
            @if(request()->routeIs('admin.category.add'))
                Thêm danh mục mới
            @else
                Sửa danh mục
            @endif
        </h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.category.list', [ 'id' => $data->parent_id ?? request()->input('parent_id') ]) }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
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
                <label>Danh mục cha</label>
                <select name="parent_id" class="form-control">
                    <option value="">Danh mục gốc</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ ($data->parent_id ?? old('parent_id') ?? request()->input('parent_id')) == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
                    @endforeach
                </select>
                @error('parent_id')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Loại danh mục</label>
                <small><i class="fas fa-question-circle text-warning" data-toggle="popover" data-html="true" data-content="Loại của danh mục con sẽ tự động theo loại của danh mục cha."></i></small>
                <div>
                    <div class="form-check-inline">
                        <input class="form-check-input @error('type') is-invalid @enderror" type="radio" id="cr-type-1" name="type" value="{{ \App\Constants\ObjectType::COURSE }}" {{ ($data->type ?? old('type')) == \App\Constants\ObjectType::COURSE ? 'checked' : '' }}>
                        <label class="form-check-label" for="cr-type-1">Khóa học</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input @error('type') is-invalid @enderror" type="radio" id="cr-type-4" name="type" value="{{ \App\Constants\ObjectType::COMBO_COURSE }}" {{ ($data->type ?? old('type')) == \App\Constants\ObjectType::COMBO_COURSE ? 'checked' : '' }}>
                        <label class="form-check-label" for="cr-type-4">Combo khoá học</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input @error('type') is-invalid @enderror" type="radio" id="cr-type-2" name="type" value="{{ \App\Constants\ObjectType::POST }}" {{ ($data->type ?? old('type')) == \App\Constants\ObjectType::POST ? 'checked' : '' }}>
                        <label class="form-check-label" for="cr-type-2">Bài viết</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input @error('type') is-invalid @enderror" type="radio" id="cr-type-3" name="type" value="{{ \App\Constants\ObjectType::BOOK }}" {{ ($data->type ?? old('type')) == \App\Constants\ObjectType::BOOK ? 'checked' : '' }}>
                        <label class="form-check-label" for="cr-type-3">Sách</label>
                    </div>
                </div>
                @error('type')
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
            <div class="form-group">
                <label>Liên kết tùy chỉnh</label>
                <small><i class="fas fa-question-circle text-warning" data-toggle="popover" data-html="true" data-content="- Liên kết tùy chỉnh<br>- Bỏ trống nếu không dùng"></i></small>
                <input type="text" name="link" placeholder="Liên kết tùy chỉnh" value="{{ $data->link ?? old('link') }}" class="form-control @error('link') is-invalid @enderror">
                @error('link')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Icon</label>
                        <div class="input-group">
                            <input type="text" name="icon" placeholder="Icon" value="{{ $data->icon ?? old('icon') }}" class="form-control @error('icon') is-invalid @enderror" id="ck-icon">
                            @error('icon')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                            <div class="input-group-append">
                                <button type="button" class="input-group-text" onclick="selectFileWithCKFinder('ck-icon', 'ck-icon-preview')">Chọn file</button>
                            </div>
                        </div>
                        <img class="image-preview" src="{{ $data->icon ?? old('icon') }}" id="ck-icon-preview">
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
                <label>Hiển thị danh mục</label>
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
                    @foreach (get_template_position(\App\Constants\ObjectType::CATEGORY) as $item)
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
            <hr>
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
            <div class="form-group">
                <label>Headings</label>
                <textarea name="headings" class="editor">{!! $data->headings ?? old('headings') !!}</textarea>
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
            el: '#js-meta-data',
            data: {
                metaTitle: `{!! $data->meta_title ?? old('meta_title') !!}`,
                metaDesc: `{!! $data->meta_description ?? old('meta_description') !!}`,
                ogTitle: `{!! $data->og_title ?? old('og_title') !!}`,
                ogDesc: `{!! $data->og_description ?? old('og_description') !!}`,
            },
        });
    </script>
@endsection
