@extends('backend.master')

@section('title')
Đơn hàng
@endsection

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Đơn hàng</h1>
    </div>
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
    <table class="table table-hover table-light table-striped">
        <thead class="bg-lightblue">
            <tr>
                <th>ID</th>
                <th>Sản phẩm</th>
                <th class="text-right">Giá trị</th>
                <th>Thành viên</th>
                <th>Thời gian</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $invoice)
                <tr>
                    <td>{{ $invoice->id }}</td>
                    <td>
                        @foreach ($invoice->items as $item)
                            @switch($item->type)
                                @case(\App\Constants\ObjectType::COURSE)
                                    <div>
                                        Khóa học:
                                        <a href="{{ $item->course->url }}" target="_blank">{{ $item->course->title }}</a>
                                    </div>
                                    @break
                                @case(\App\Constants\ObjectType::COMBO_COURSE)
                                    <div>
                                        Combo khóa học:
                                        <a href="{{ $item->comboCourse->url }}" target="_blank">{{ $item->comboCourse->title }}</a>
                                    </div>
                                    @break
                                @case(\App\Constants\ObjectType::BOOK)
                                    <div>
                                        Sách:
                                        <a href="{{ $item->book->url }}" target="_blank">{{ $item->book->title }}</a>
                                    </div>
                                    @break
                            @endswitch
                        @endforeach
                    </td>
                    <td class="text-right">{{ currency($invoice->total_price, '0 ₫') }}</td>
                    <td>{{ $invoice->user->username }}</td>
                    <td>{{ $invoice->created_at->format('d-m-Y H:i') }}</td>
                    <td>
                        @switch($invoice->status)
                            @case(\App\Constants\InvoiceStatus::PENDING)
                                <span class="badge badge-sm badge-warning">Đang xử lý</span>
                                @break
                            @case(\App\Constants\InvoiceStatus::COMPLETE)
                                <span class="badge badge-sm badge-success">Đã hoàn thành</span>
                                @break
                            @case(\App\Constants\InvoiceStatus::CANCEL)
                                <span class="badge badge-sm badge-success">Đã hủy bỏ</span>
                                @break
                        @endswitch
                    </td>
                    <td>
                        <a href="{{ route('admin.invoice.detail', [ 'id' => $invoice->id ]) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-search"></i>
                            Chi tiết
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $list->withQueryString()->links() }}
@endsection

@section('script')

@endsection
