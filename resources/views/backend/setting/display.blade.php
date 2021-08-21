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
@endsection
