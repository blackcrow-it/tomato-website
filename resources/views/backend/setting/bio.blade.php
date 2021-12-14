@extends('backend.setting.master')

@section('setting_title')
Cài đặt thông tin Landing Bio
@endsection

@section('setting_content')
<div class="form-group form-row">
    <div class="col">
        <label>Ảnh nền</label>
        <div class="input-group">
            <input type="text" name="bio-background" :value="getValue('bio-background', '{{ config('settings.bio-background') }}')" class="form-control">
            <div class="input-group-append">
                <button type="button" class="input-group-text" @click="uploadImage('bio-background')">Chọn file</button>
            </div>
        </div>
        <img class="image-preview" :src="getValue('bio-background', '{{ config('settings.bio-background') }}')">
    </div>
    <div class="col">
        <label>Ảnh đại diện (khuyến khích hình vuông)</label>
        <div class="input-group">
            <input type="text" name="bio-avatar" :value="getValue('bio-avatar', '{{ config('settings.bio-avatar') }}')" class="form-control">
            <div class="input-group-append">
                <button type="button" class="input-group-text" @click="uploadImage('bio-avatar')">Chọn file</button>
            </div>
        </div>
        <img class="image-preview" :src="getValue('bio-avatar', '{{ config('settings.bio-avatar') }}')">
    </div>
</div>
<div class="form-group">
    <label>Tiêu đề đầu trang</label>
    <input type="text" name="bio-title-header" value="{{ config('settings.bio-title-header') }}" placeholder="Kết nối với Ngoại Ngữ Tomato" class="form-control" >
</div>
<div class="form-group form-row">
    <div class="col">
        <label>Email</label>
        <input type="text" name="bio-email" value="{{ config('settings.bio-email') }}" class="form-control">
    </div>
    <div class="col">
        <label>Hotline</label>
        <input type="text" name="bio-hotline" value="{{ config('settings.bio-hotline') }}" class="form-control" >
    </div>
</div>

<div v-for="(item, index) in bioItems">
    <div class="form-group form-row">
        <div class="col">
            <label>Icon @{{ index + 1 }}</label>
            <div class="input-group">
                <img class="input-group-prepend" style="border-radius: 50%;" width="38px" height="38px" :src="item.linkIcon">
                <input type="text" :name="'bio-link-icon-' + index" v-model="item.linkIcon" class="form-control">
                <div class="input-group-append">
                    <button type="button" class="input-group-text" @click="uploadImageIconBio(index)">Chọn file</button>
                </div>
            </div>
        </div>
        <div class="col">
            <label>Tiêu đề</label>
            <input type="text" v-model="item.title" class="form-control" >
        </div>
        <div class="col">
            <label>Giá trị</label>
            <input type="text" v-model="item.link" class="form-control" >
        </div>
        <div class="col">
            <label>Loại</label>
            <select v-model="item.type" class="form-control">
                <option value="link">Đường dẫn</option>
                <option value="phone">Số điện thoại</option>
                <option value="email">Email</option>
            </select>
        </div>
        <div class="col">
            <label>Thao tác</label><br/>
            <button type="button" class="btn btn-danger" @click="deleteLinkBio(index)">Xoá</button>
        </div>
    </div>
</div>
<input type="hidden" id="bio-item" name="bio_item">
<button type="button" class="btn btn-primary" @click="addLinkBio()">Thêm đường dẫn</button>
<br/>
@endsection
