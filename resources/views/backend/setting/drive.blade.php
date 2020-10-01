@extends('backend.setting.master')

@section('setting_title')
Cài đặt google drive
@endsection

@section('setting_content')
@if(config('settings.google_drive_refresh_token') != '')
    <a href="{{ route('admin.setting.drive.redirect') }}" class="btn btn-success"><i class="fab fa-google-drive"></i> Kết nối lại</a>
@else
    <a href="{{ route('admin.setting.drive.redirect') }}" class="btn btn-warning"><i class="fab fa-google-drive"></i> Kết nối ngay</a>
@endif
@endsection
