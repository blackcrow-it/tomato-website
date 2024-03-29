@extends('backend.master')

@section('title')
Thành viên
@endsection

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Thành viên</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.user.add') }}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Thêm mới</a>
        </div>
    </div><!-- /.col -->
</div>

<form action="" method="GET">
    @csrf
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label>ID</label>
                <input type="text" class="form-control" name="filter[id]" value="{{ request()->input('filter.id') }}" placeholder="ID">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label>Tài khoản</label>
                <input type="text" class="form-control" name="filter[username]" value="{{ request()->input('filter.username') }}" placeholder="Tài khoản">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="filter[email]" value="{{ request()->input('filter.email') }}" placeholder="Email">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label>Họ tên</label>
                <input type="text" class="form-control" name="filter[name]" value="{{ request()->input('filter.name') }}" placeholder="Họ tên">
            </div>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
    </div>
</form>
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

<div class="table-responsive">
    <table class="table table-hover table-light">
        <thead class="bg-lightblue">
            <tr>
                <th>ID</th>
                <th>Ảnh đại diện</th>
                <th>Tài khoản</th>
                <th>Email</th>
                <th>Họ tên</th>
                <th>Số tiền</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($list as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>
                        <img src="{{ $item->avatar }}" class="img-thumbnail">
                    </td>
                    <td>{{ $item->username }}</td>
                    <td>
                        @if($item->email_verified_at)
                            {{ $item->email }}
                        @else
                            <em class="text-secondary" data-toggle="tooltip" data-placement="top" title="Email chưa được xác thực">{{ $item->email }}</em>
                        @endif
                    </td>
                    <td>{{ $item->name }}</td>
                    <td>{{ currency($item->money, 0) }}</td>
                    <td class="text-nowrap">
                        <form action="{{ route('admin.user.delete', [ 'id' => $item->id ]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa thành viên này?')">
                            @csrf
                            <a href="{{ route('admin.user.edit', [ 'id' => $item->id ]) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Sửa</a>
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $list->withQueryString()->links() }}
@endsection
