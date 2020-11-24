@extends('backend.setting.master')

@section('setting_title')
Cài đặt thông báo qua email
@endsection

@section('setting_content')
<div class="form-group">
    <label>Email nhận thông báo</label>
    <input type="email" class="form-control" name="email_notification" value="{{ config('settings.email_notification') }}">
</div>
@endsection
