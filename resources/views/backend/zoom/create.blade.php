@extends('backend.master')

@section('title')
Lên lịch học Zoom
@endsection

@section('content-header')
<style>
    .card {
        width: 700px;
        margin: auto;
    }
    #form__create__zoom {
        width: 600px;
    }
    #form__create__zoom tr>td {
        padding-bottom: 1rem;
        padding-right: 1rem;
    }
</style>
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Lên lịch học Zoom</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.zoom.index') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
        </div>
    </div><!-- /.col -->
</div>
@endsection

@section('content')
<div>
<form action="{{ route('admin.zoom.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <table id="form__create__zoom">
                        <tr>
                            <td>Chủ đề</td>
                            <td><input type="text" class="form-control form-control-sm" name="topic" placeholder="Topic"></td>
                        </tr>
                        <tr>
                            <td>Mô tả (không bắt buộc)</td>
                            <td><textarea class="form-control form-control-sm" name="agenda"></textarea></td>
                        </tr>
                        <tr>
                            <td>Khi nào</td>
                            <td><input type="datetime-local" class="form-control form-control-sm" name="start_time"></td>
                        </tr>
                        <tr>
                            <td>Thời lượng (phút)</td>
                            <td><input type="number" class="form-control form-control-sm" name="duration" placeholder="Phút"></td>
                        </tr>
                        <tr>
                            <td>Bảo mật</td>
                            <td><input type="password" class="form-control form-control-sm" name="password" placeholder="Mật khẩu"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><button class="btn btn-primary">Lưu</button> <a href="{{ route('admin.zoom.index') }}" class="btn btn-outline-secondary">Huỷ</a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
</div>
@endsection
