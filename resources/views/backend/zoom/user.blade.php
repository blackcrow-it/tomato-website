@extends('backend.master')

@section('title')
Danh sách tài khoản Zoom
@endsection

@section('content-header')
<style>
    .card-user {
        transition: .3s all;
        cursor: pointer;
    }
    .card-user:hover {
        box-shadow: 0 0 3px rgb(0 0 0 / 13%), 0 3px 9px rgb(0 0 0 / 20%);
        margin-top: -5px;
    }
</style>
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Danh sách tài khoản Zoom</h1>
    </div><!-- /.col -->
    {{-- <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.zoom.new') }}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Lên lịch</a>
        </div>
    </div><!-- /.col --> --}}
</div>
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
<div class="row">
    @foreach (array_reverse($data['data']['users']) as $user)
    <div class="col-md-4">
        <a href="{{ route('admin.zoom.meetings', ['id' => $user['id']]) }}" style="color: #000">
            <div class="card card-widget widget-user-2 card-user">
            <div class="widget-user-header">
                <div class="widget-user-image">
                <img class="img-circle elevation-2" src="{{ $user['pic_url'] }}" alt="User Avatar">
                </div>
                <h3 class="widget-user-username">{{ $user['last_name'] }} {{ $user['first_name'] }}</h3>
                <h5 class="widget-user-desc">{{ $user['email'] }}</h5>
            </div>
            </div>
        </a>
    </div>
    @endforeach
</div>
@endsection
@section('script')
<script>
    $(document).ready(function () {
        $(".form__delete_zoom").submit(function (e) {
            $(this).find(':input[type=submit]').prop('disabled', true);
        });
    });
</script>
@endsection
