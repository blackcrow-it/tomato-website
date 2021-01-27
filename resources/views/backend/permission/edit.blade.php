@extends('backend.master')

@section('title')
@if(request()->routeIs('admin.permission.add'))
    Thêm nhóm mới
@else
    Sửa thông tin nhóm
@endif
@endsection

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">
            @if(request()->routeIs('admin.permission.add'))
                Thêm nhóm mới
            @else
                Sửa thông tin nhóm
            @endif
        </h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.permission.list') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
        </div>
    </div><!-- /.col -->
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

<style>
table tr td:first-child{
    width: 70px;
    text-align: center;
}
</style>

<div class="card">
    <form action="" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Tên nhóm</label>
                <input type="text" name="role[name]" placeholder="Tên" value="{{ old('role.name') ?? $role->name ?? null }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Quyền hạn</label>
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox" name="permissions[]" value="course.list" {{ in_array('course.list', old('permissions') ?? $permissions ?? []) ? 'checked' : '' }}>
                            </td>
                            <td>Hiển thị danh sách khóa học</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="permissions[]" value="course.add" {{ in_array('course.add', old('permissions') ?? $permissions ?? []) ? 'checked' : '' }}>
                            </td>
                            <td>Thêm khóa học</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="permissions[]" value="course.edit" {{ in_array('course.edit', old('permissions') ?? $permissions ?? []) ? 'checked' : '' }}>
                            </td>
                            <td>Sửa khóa học</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="permissions[]" value="course.delete" {{ in_array('course.delete', old('permissions') ?? $permissions ?? []) ? 'checked' : '' }}>
                            </td>
                            <td>Xóa khóa học</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox" name="permissions[]" value="post.list" {{ in_array('post.list', old('permissions') ?? $permissions ?? []) ? 'checked' : '' }}>
                            </td>
                            <td>Hiển thị danh sách bài viết</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="permissions[]" value="post.add" {{ in_array('post.add', old('permissions') ?? $permissions ?? []) ? 'checked' : '' }}>
                            </td>
                            <td>Thêm bài viết</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="permissions[]" value="post.edit" {{ in_array('post.edit', old('permissions') ?? $permissions ?? []) ? 'checked' : '' }}>
                            </td>
                            <td>Sửa bài viết</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="permissions[]" value="post.delete" {{ in_array('post.delete', old('permissions') ?? $permissions ?? []) ? 'checked' : '' }}>
                            </td>
                            <td>Xóa bài viết</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox" name="permissions[]" value="book.list" {{ in_array('book.list', old('permissions') ?? $permissions ?? []) ? 'checked' : '' }}>
                            </td>
                            <td>Hiển thị danh sách tài liệu</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="permissions[]" value="book.add" {{ in_array('book.add', old('permissions') ?? $permissions ?? []) ? 'checked' : '' }}>
                            </td>
                            <td>Thêm tài liệu</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="permissions[]" value="book.edit" {{ in_array('book.edit', old('permissions') ?? $permissions ?? []) ? 'checked' : '' }}>
                            </td>
                            <td>Sửa tài liệu</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="permissions[]" value="book.delete" {{ in_array('book.delete', old('permissions') ?? $permissions ?? []) ? 'checked' : '' }}>
                            </td>
                            <td>Xóa tài liệu</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox" name="permissions[]" value="category.list" {{ in_array('category.list', old('permissions') ?? $permissions ?? []) ? 'checked' : '' }}>
                            </td>
                            <td>Hiển thị danh sách danh mục</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="permissions[]" value="category.add" {{ in_array('category.add', old('permissions') ?? $permissions ?? []) ? 'checked' : '' }}>
                            </td>
                            <td>Thêm danh mục</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="permissions[]" value="category.edit" {{ in_array('category.edit', old('permissions') ?? $permissions ?? []) ? 'checked' : '' }}>
                            </td>
                            <td>Sửa danh mục</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="permissions[]" value="category.delete" {{ in_array('category.delete', old('permissions') ?? $permissions ?? []) ? 'checked' : '' }}>
                            </td>
                            <td>Xóa danh mục</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox" name="permissions[]" value="teacher.list" {{ in_array('teacher.list', old('permissions') ?? $permissions ?? []) ? 'checked' : '' }}>
                            </td>
                            <td>Hiển thị danh sách giảng viên</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="permissions[]" value="teacher.add" {{ in_array('teacher.add', old('permissions') ?? $permissions ?? []) ? 'checked' : '' }}>
                            </td>
                            <td>Thêm giảng viên</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="permissions[]" value="teacher.edit" {{ in_array('teacher.edit', old('permissions') ?? $permissions ?? []) ? 'checked' : '' }}>
                            </td>
                            <td>Sửa giảng viên</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="permissions[]" value="teacher.delete" {{ in_array('teacher.delete', old('permissions') ?? $permissions ?? []) ? 'checked' : '' }}>
                            </td>
                            <td>Xóa giảng viên</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox" name="permissions[]" value="recharge.list" {{ in_array('recharge.list', old('permissions') ?? $permissions ?? []) ? 'checked' : '' }}>
                            </td>
                            <td>Hiển thị thông tin nạp tiền</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox" name="permissions[]" value="invoice.list" {{ in_array('invoice.list', old('permissions') ?? $permissions ?? []) ? 'checked' : '' }}>
                            </td>
                            <td>Hiển thị thông tin đơn hàng</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox" name="permissions[]" value="promo.list" {{ in_array('promo.list', old('permissions') ?? $permissions ?? []) ? 'checked' : '' }}>
                            </td>
                            <td>Hiển thị thông tin mã khuyến mãi</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary"><i class="fas fa-save"></i> Lưu</button>
        </div>
    </form>
</div>
@endsection
