@extends('frontend.master')

@section('header')
    <title>Đăng nhập</title>
    <meta content="description" value="">
    <meta property="og:title" content="">
    <meta property="og:description" content="">
    <meta property="og:url" content="">
    <meta property="og:image" content="">
    <script type="text/javascript" src="{{ asset('js/qrcode.min.js') }}"></script>
@endsection

@section('body')
{!!$data['google2fa_url']!!}

@endsection
