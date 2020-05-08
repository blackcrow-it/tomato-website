@extends('backend.master')

@section('title')
    @if (request()->routeIs('admin.category.add'))
        Thêm danh mục mới
    @else
        Sửa danh mục
    @endif
@endsection

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">
            @if (request()->routeIs('admin.category.add'))
                Thêm danh mục mới
            @else
                Sửa danh mục
            @endif
        </h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.category.list', [ 'category' => $data->parent ?? request()->input('parent_id') ]) }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
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

<div class="card">
    <form action="" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Danh mục cha</label>
                <select name="parent_id" class="form-control">
                    <option value="">Danh mục gốc</option>
                    @foreach ($categories as $category)
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
                        <input class="form-check-input @error('type') is-invalid @enderror" type="radio" id="cr-type-1" name="type" value="{{ \App\Category::TYPE_COURSE }}" {{ ($data->type ?? old('type')) == \App\Category::TYPE_COURSE ? 'checked' : '' }}>
                        <label class="form-check-label" for="cr-type-1">Khóa học</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input @error('type') is-invalid @enderror" type="radio" id="cr-type-2" name="type" value="{{ \App\Category::TYPE_POST }}" {{ ($data->type ?? old('type')) == \App\Category::TYPE_POST ? 'checked' : '' }}>
                        <label class="form-check-label" for="cr-type-2">Bài viết</label>
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
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Icon</label>
                        <small><i class="fas fa-question-circle text-warning" data-toggle="popover" data-html="true" data-content="Sử dụng bộ icon miễn phí của Font Awesome."></i></small>
                        <input type="text" name="icon" placeholder="Icon (vd: fas fa-check-circle)" value="{{ $data->icon ?? old('icon') }}" id="c-icon" class="form-control custom-icon-input @error('icon') is-invalid @enderror">
                        @error('icon')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                        <div class="custom-icon-preview text-center mt-2" for="c-icon">
                            <i class=""></i>
                        </div>
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

@section('style')
<style>
    .custom-icon-preview {
        font-size: 2.5rem;
    }
</style>
@endsection

@section('script')
<script>
    var updateIconPreview = function() {
        var id = $(this).attr('id');
        var preview = $(this).parent().find('.custom-icon-preview i');

        $(preview).attr('class', $(this).val());
    }

    $('.custom-icon-input').on('keyup change', updateIconPreview);

    $('.custom-icon-input').each(updateIconPreview);
</script>
@endsection
