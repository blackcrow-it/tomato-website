@extends('backend.master')

@section('title')
Cài đặt trang chủ
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
    <form action="{{ route('admin.setting.submit') }}" method="POST">
        @csrf
        <div class="card-header">
            <h3>Cài đặt trang chủ</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="homepage_title" value="{{ config('settings.homepage_title') }}" class="form-control" placeholder="Title">
            </div>
            <div class="form-group">
                <label>Keywords</label>
                <input type="text" name="homepage_keywords" value="{{ config('settings.homepage_keywords') }}" class="form-control" placeholder="Keywords">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="homepage_description" rows="3" class="form-control" placeholder="Description">{{ config('settings.homepage_description') }}</textarea>
            </div>
            <div class="form-group">
                <label>Headings</label>
                <textarea name="homepage_headings" class="editor">{!! config('settings.homepage_headings') !!}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu</button>
        </div>
    </form>
</div>
@endsection
