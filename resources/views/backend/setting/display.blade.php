@extends('backend.setting.master')

@section('setting_title')
Hiển thị
@endsection

@section('setting_content')
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label>Ảnh nền đăng ký nhận tin</label>
            <div class="input-group">
                <input type="text" name="consultation_background" placeholder="Đăng ký nhận tin" :value="getValue('consultation_background', '{{ config('settings.consultation_background') }}')" class="form-control">
                <div class="input-group-append">
                    <button type="button" class="input-group-text" @click="uploadImage('consultation_background')">Chọn file</button>
                </div>
            </div>
            <img class="image-preview" :src="getValue('consultation_background', '{{ config('settings.consultation_background') }}')">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label>Ảnh nền trang đăng nhập</label>
            <div class="input-group">
                <input type="text" name="login_background" placeholder="Đăng nhập" :value="getValue('login_background', '{{ config('settings.login_background') }}')" class="form-control">
                <div class="input-group-append">
                    <button type="button" class="input-group-text" @click="uploadImage('login_background')">Chọn file</button>
                </div>
            </div>
            <img class="image-preview" :src="getValue('login_background', '{{ config('settings.login_background') }}')">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label>Ảnh nền trang đăng ký</label>
            <div class="input-group">
                <input type="text" name="register_background" placeholder="Đăng ký" :value="getValue('register_background', '{{ config('settings.register_background') }}')" class="form-control">
                <div class="input-group-append">
                    <button type="button" class="input-group-text" @click="uploadImage('register_background')">Chọn file</button>
                </div>
            </div>
            <img class="image-preview" :src="getValue('register_background', '{{ config('settings.register_background') }}')">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label>Ảnh nền trang quên mật khẩu</label>
            <div class="input-group">
                <input type="text" name="forgot_password_background" placeholder="Quên mật khẩu" :value="getValue('forgot_password_background', '{{ config('settings.forgot_password_background') }}')" class="form-control">
                <div class="input-group-append">
                    <button type="button" class="input-group-text" @click="uploadImage('forgot_password_background')">Chọn file</button>
                </div>
            </div>
            <img class="image-preview" :src="getValue('forgot_password_background', '{{ config('settings.forgot_password_background') }}')">
        </div>
    </div>
</div>
@endsection
