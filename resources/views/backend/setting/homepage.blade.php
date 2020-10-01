@extends('backend.setting.master')

@section('setting_content')
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
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label>OG Image</label>
            <div class="input-group">
                <input type="text" name="homepage_og_image" placeholder="OG Image" :value="getValue('homepage_og_image', '{{ config('settings.homepage_og_image') }}')" class="form-control">
                <div class="input-group-append">
                    <button type="button" class="input-group-text" @click="uploadImage('homepage_og_image')">Chọn file</button>
                </div>
            </div>
            <img class="image-preview" :src="getValue('homepage_og_image', '{{ config('settings.homepage_og_image') }}')">
        </div>
    </div>
</div>
<div class="form-group">
    <label>OG Title</label>
    <input type="text" name="homepage_og_title" value="{{ config('settings.homepage_og_title') }}" class="form-control" placeholder="OG Title">
</div>
<div class="form-group">
    <label>OG Description</label>
    <textarea name="homepage_og_description" rows="3" class="form-control" placeholder="OG Description">{{ config('settings.homepage_og_description') }}</textarea>
</div>
@endsection
