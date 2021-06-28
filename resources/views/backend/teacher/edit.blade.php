@extends('backend.master')

@section('title')
@if(request()->routeIs('admin.teacher.add'))
    Thêm giảng viên mới
@else
    Sửa thông tin giảng viên
@endif
@endsection

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">
            @if(request()->routeIs('admin.teacher.add'))
                Thêm giảng viên mới
            @else
                Sửa thông tin giảng viên
            @endif
        </h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.teacher.list') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
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
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Tên</label>
                        <input type="text" name="teacher[name]" placeholder="Tên" value="{{ old('teacher.name') ?? $teacher->name ?? null }}" class="form-control @error('teacher.name') is-invalid @enderror" required>
                        @error('teacher.name')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="teacher[email]" placeholder="Email" value="{{ old('teacher.email') ?? $teacher->email ?? null }}" class="form-control @error('teacher.email') is-invalid @enderror">
                        @error('teacher.email')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Ảnh đại diện</label>
                        <div class="input-group">
                            <input type="text" name="teacher[avatar]" placeholder="Ảnh thu nhỏ" value="{{ old('teacher.avatar') ?? $teacher->avatar ?? null }}" class="form-control @error('teacher.avatar') is-invalid @enderror" id="ck-thumbnail">
                            @error('teacher.avatar')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                            <div class="input-group-append">
                                <button type="button" class="input-group-text" onclick="selectFileWithCKFinder('ck-thumbnail', 'ck-thumbnail-preview')">Chọn file</button>
                            </div>
                        </div>
                        <img class="image-preview" src="{{ old('teacher.avatar') ?? $teacher->avatar ?? null }}" id="ck-thumbnail-preview">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Mô tả</label>
                <textarea name="teacher[description]" class="editor">{!! old('teacher.description') ?? $teacher->description ?? null !!}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary"><i class="fas fa-save"></i> Lưu</button>
        </div>
    </form>
</div>
@endsection
