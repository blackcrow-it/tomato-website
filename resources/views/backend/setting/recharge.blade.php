@extends('backend.setting.master')

@section('setting_title')
Cài đặt nạp tiền
@endsection

@section('setting_content')
<div class="form-group">
    <label>Thông tin chuyển khoản trực tiếp</label>
    <textarea name="recharge_direct_info" class="editor">{!! config('settings.recharge_direct_info') !!}</textarea>
</div>
<div class="form-group">
    <label>Bài viết hướng dẫn nạp tiền</label>
    <textarea name="recharge_tutorial_info" class="editor">{!! config('settings.recharge_tutorial_info') !!}</textarea>
</div>
@endsection
