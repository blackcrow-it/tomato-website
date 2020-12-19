@extends('backend.master')

@section('title')
Mã khuyến mãi
@endsection

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Mã khuyến mãi</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.promo.add') }}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Thêm mới</a>
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

<div class="table-responsive">
    <table class="table table-hover table-light">
        <thead class="bg-lightblue">
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>Loại</th>
                <th>Giá trị</th>
                <th>Thời gian kết thúc</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($list as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->code }}</td>
                    <td>
                        @switch($item->type)
                            @case(\App\Constants\PromoType::DISCOUNT)
                                Giảm giá
                                @break
                            @case(\App\Constants\PromoType::SAME_PRICE)
                                Đồng giá
                                @break
                        @endswitch
                        <br>
                        @if($item->used_many_times)
                            Dùng nhiều lần
                        @else
                            Dùng một lần
                        @endif
                    </td>
                    <td>
                        @switch($item->type)
                            @case(\App\Constants\PromoType::DISCOUNT)
                                {{ $item->value }}%
                                @break
                            @case(\App\Constants\PromoType::SAME_PRICE)
                                {{ currency($item->value) }}
                                @break
                        @endswitch
                    </td>
                    <td>{{ $item->expires_on->format('Y-m-d H:i') }}</td>
                    <td class="text-nowrap">
                        <form action="{{ route('admin.promo.delete', [ 'id' => $item->id ]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mã khuyến mãi này?')">
                            @csrf
                            <a href="{{ route('admin.promo.edit', [ 'id' => $item->id ]) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Sửa</a>
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
