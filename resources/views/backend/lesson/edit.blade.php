@extends('backend.master')

@section('title')
    @if (request()->routeIs('admin.course.add'))
        Thêm bài học mới
    @else
        Sửa bài học
    @endif
@endsection

@section('content-header')
<div class="row">
    <div class="col-sm-2">
        <img src="{{ $course->thumbnail }}">
    </div>
    <div class="col-sm-10">
        <strong>{{ $course->title }}</strong>
        <br>
        <a href="{{ route('course', [ 'slug' => $course->slug ]) }}" target="_blank"><small><em>{{ route('course', [ 'slug' => $course->slug ]) }}</em></small></a>
        <br>
        {{ $course->description }}
    </div>
</div>
<hr>
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">
            @if (request()->routeIs('admin.lesson.add'))
                Thêm bài học mới
            @else
                Sửa bài học
            @endif
        </h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.lesson.list', [ 'course_id' => $course->id ]) }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
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
                <label>Tiêu đề</label>
                <input type="text" name="title" placeholder="Tiêu đề" value="{{ $data->title ?? old('title') }}" class="form-control @error('title') is-invalid @enderror">
                @error('title')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label>Hiển thị bài học</label>
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
        </div>
        <div class="card-footer">
            <button class="btn btn-primary"><i class="fas fa-save"></i> Lưu</button>
        </div>
    </form>
</div>
@endsection
