@extends('backend.setting.master')

@section('setting_title')
Cài đặt thông tin Landing Bio
@endsection

@section('setting_content')
<div class="form-group">
    <label>Ảnh đại diện (khuyến khích hình vuông)</label>
    <div class="input-group">
        <input type="text" name="bio-avatar" :value="getValue('bio-avatar', '{{ config('settings.bio-avatar') }}')" class="form-control">
        <div class="input-group-append">
            <button type="button" class="input-group-text" @click="uploadImage('bio-avatar')">Chọn file</button>
        </div>
    </div>
    <img class="image-preview" :src="getValue('bio-avatar', '{{ config('settings.bio-avatar') }}')">
</div>
<div class="form-group form-row">
    <div class="col">
        <label>Tiêu đề link Youtube 1</label>
        <input type="text" name="bio-title-youtube-1" value="{{ config('settings.bio-title-youtube-1') }}" class="form-control" >
    </div>
    <div class="col">
        <label>Link Youtube 1</label>
        <input type="text" name="bio-link-youtube-1" value="{{ config('settings.bio-link-youtube-1') }}" class="form-control" >
    </div>
</div>
<div class="form-group form-row">
    <div class="col">
        <label>Tiêu đề link Youtube 2</label>
        <input type="text" name="bio-title-youtube-2" value="{{ config('settings.bio-title-youtube-2') }}" class="form-control" >
    </div>
    <div class="col">
        <label>Link Youtube 2</label>
        <input type="text" name="bio-link-youtube-2" value="{{ config('settings.bio-link-youtube-2') }}" class="form-control" >
    </div>
</div>
<div class="form-group">
    <label>Link Fanpage</label>
    <input type="text" name="bio-link-fanpage" value="{{ config('settings.bio-link-fanpage') }}" class="form-control">
</div>
<div class="form-group">
    <label>Email</label>
    <input type="text" name="bio-gmail" value="{{ config('settings.bio-gmail') }}" class="form-control">
</div>
<div class="form-group">
    <label>Link Zalo</label>
    <input type="text" name="bio-link-zalo" value="{{ config('settings.bio-link-zalo') }}" class="form-control" >
</div>
<div class="form-group">
    <label>Link playlist video hay nhất</label>
    <input type="text" name="bio-link-playlist" value="{{ config('settings.bio-link-playlist') }}" class="form-control" >
</div>
<div class="form-group">
    <label>Link Podcast</label>
    <input type="text" name="bio-link-podcast" value="{{ config('settings.bio-link-podcast') }}" class="form-control" >
</div>
<div class="form-group">
    <label>Link Skype</label>
    <input type="text" name="bio-link-skype" value="{{ config('settings.bio-link-skype') }}" class="form-control" >
</div>
<div class="form-group">
    <label>Link Telegram</label>
    <input type="text" name="bio-link-telegram" value="{{ config('settings.bio-link-telegram') }}" class="form-control" >
</div>
<div class="form-group">
    <label>Link Linkedin</label>
    <input type="text" name="bio-link-linkedin" value="{{ config('settings.bio-link-linkedin') }}" class="form-control" >
</div>
<div class="form-group">
    <label>Hotline</label>
    <input type="text" name="bio-hotline" value="{{ config('settings.bio-hotline') }}" class="form-control" >
</div>
@endsection
