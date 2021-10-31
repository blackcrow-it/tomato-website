@extends('backend.master')

@section('title')
Chi tiết đơn hàng
@endsection

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">
            Chi tiết đơn hàng
        </h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.invoice.list') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
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

<div class="invoice p-3 mb-3">
    <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
            <b>Thông tin người nhận</b>
            <address>
                <div>Họ tên: <b>{{ $invoice->name }}</b></div>
                <div>Số điện thoại: <b>{{ $invoice->phone }}</b></div>
                <div>
                    Hình thức:
                    @if($invoice->shipping)
                        <b>Giao hàng</b>
                    @else
                        <b>Nhận hàng tại trung tâm</b>
                    @endif
                </div>
                @if($invoice->shipping)
                    @if($invoice->shipment)
                        <div>Đơn vị vận chuyển:
                            <b>{{ $invoice->shipment->partner }}</b>
                        </div>
                        <div>PT giao hàng:
                            @if($invoice->shipment->is_fast)
                            <b>Giao hàng nhanh</b>
                            @else
                            <b>Giao hàng tiết kiệm</b>
                            @endif
                        </div>
                        <div>PT thanh toán:<br/>
                            @if($invoice->shipment->is_ship_cod)
                            <b>Nhận hàng rồi trả tiền (COD)</b>
                            @else
                            <b>Sử dụng số dư tài khoản (Đã thanh toán)</b>
                            @endif
                        </div>
                    @endif
                    <div>Địa chỉ: <b>{{ $invoice->address }}</b></div>
                    <div>Quận, huyện: <b>{{ $invoice->district }}</b></div>
                    <div>Tỉnh, thành phố: <b>{{ $invoice->city }}</b></div>
                @endif
            </address>
        </div>
        <div class="col-sm-4 invoice-col">
            <b>Thành viên đặt hàng</b>
            <address>
                <div>Tài khoản: <b>{{ $invoice->user->username }}</b></div>
                <div>Họ tên: <b>{{ $invoice->user->name }}</b></div>
                <div>Số điện thoại: <b>{{ $invoice->user->phone }}</b></div>
                <div>Địa chỉ: <b>{{ $invoice->user->address }}</b></div>
                <div>Số dư: <b>{{ currency($invoice->user->money, '0 ₫') }}</b></div>
            </address>
        </div>
        <div class="col-sm-4 invoice-col">
            <div><b>Đơn hàng #{{ $invoice->id }}</b></div>
            <div><b>Tạo lúc:</b> {{ $invoice->created_at->format('d-m-Y H:i:s') }}</div>
        </div>
    </div>

    @if ($invoice->promo)
        <div class="mb-3">
            <div>
                <b>Mã khuyến mãi</b>
            </div>
            <div class="text-danger">{{ $invoice->promo->code }}</div>
            <div>
                @switch($invoice->promo->type)
                    @case(\App\Constants\PromoType::DISCOUNT)
                        Giảm giá {{ $invoice->promo->value }}%
                        @break
                    @case(\App\Constants\PromoType::SAME_PRICE)
                        Đồng giá {{ currency($invoice->promo->value) }}
                        @break
                @endswitch
            </div>
        </div>
    @endif

    <div class="table-responsive mb-3">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Loại</th>
                    <th>Tiêu đề</th>
                    <th class="text-right">Số lượng</th>
                    <th class="text-right">Đơn giá</th>
                    <th class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $total = 0;
                    $shipment_fee = 0;
                ?>
                @foreach($invoice->items as $item)
                    <?php
                        $subTotal = $item->price * $item->amount;
                        $total += $subTotal;
                        if ($item->type == 'shipment_fee') {
                            $shipment_fee = $item->price;
                        }
                    ?>
                    @if ($item->type != 'shipment_fee')
                    <tr>
                        <td>
                            @switch($item->type)
                                @case(\App\Constants\ObjectType::COURSE)
                                    Khóa học
                                    @break
                                @case(\App\Constants\ObjectType::COMBO_COURSE)
                                    Combo khóa học
                                    @break
                                @case(\App\Constants\ObjectType::BOOK)
                                    Sách
                                    @break
                            @endswitch
                        </td>
                        <td>
                            @switch($item->type)
                                @case(\App\Constants\ObjectType::COURSE)
                                    <a href="{{ $item->course->url }}" target="_blank">{{ $item->course->title }}</a><br>
                                    <em><small>Giá: {{ currency($item->course->price) }}</small></em>
                                    @break
                                @case(\App\Constants\ObjectType::COMBO_COURSE)
                                    <a href="{{ $item->comboCourse->url }}" target="_blank">{{ $item->comboCourse->title }}</a><br>
                                    <em><small>Giá: {{ currency($item->comboCourse->price) }}</small></em>
                                    @break
                                @case(\App\Constants\ObjectType::BOOK)
                                    <a href="{{ $item->book->url }}" target="_blank">{{ $item->book->title }}</a><br>
                                    <em><small>Giá: {{ currency($item->book->price) }}</small></em>
                                    @break
                            @endswitch
                        </td>
                        <td class="text-right">{{ $item->amount }}</td>
                        <td class="text-right">{{ currency($item->price) }}</td>
                        <td class="text-right">{{ currency($subTotal) }}</td>
                    </tr>
                    @endif
                @endforeach
                <?php $total += 0; ?>
                @if ($invoice->shipment)
                <tr>
                    <th colspan="4" class="text-right">Phí vận chuyển</th>
                    <td class="text-right">{{ currency($invoice->shipment->ship_money) }}</td>
                </tr>
                @endif
                <tr>
                    <th colspan="4" class="text-right">Tổng cộng</th>
                    @if ($invoice->shipment)
                    <td class="text-right">{{ currency($total + $invoice->shipment->ship_money, '0 ₫') }}</td>
                    @else
                    <td class="text-right">{{ currency($total, '0 ₫') }}</td>
                    @endif
                </tr>
            </tbody>
        </table>
    </div>

    <div class="text-center">
        <b>Tình trạng đơn hàng</b>
        <h1>
            @switch($invoice->status)
                @case(\App\Constants\InvoiceStatus::COMPLETE)
                    <span class="text-green">Đã hoàn thành</span>
                    @break
                @case(\App\Constants\InvoiceStatus::PENDING)
                    <span class="text-warning">Đang xử lý</span>
                    @break
                @case(\App\Constants\InvoiceStatus::CANCEL)
                    <span class="text-danger">Hủy bỏ</span>
                    @break
            @endswitch
        </h1>
        <button type="button" class="btn btn-link btn-sm mb-2" data-toggle="collapse" href="#change-status">Thay đổi</button>
        <div class="collapse" id="change-status">
            <form action="{{ route('admin.invoice.change_status', [ 'id' => $invoice->id ]) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn thay đổi trạng thái đơn hàng?')">
                @csrf
                <div class="d-flex justify-content-center align-items-center">
                    <select name="status" class="form-control" style="max-width: 250px">
                        <option value="{{ \App\Constants\InvoiceStatus::COMPLETE }}" {{ $invoice->status == \App\Constants\InvoiceStatus::COMPLETE ? 'selected' : null }}>Đã hoàn thành</option>
                        <option value="{{ \App\Constants\InvoiceStatus::PENDING }}" {{ $invoice->status == \App\Constants\InvoiceStatus::PENDING ? 'selected' : null }}>Đang xử lý</option>
                        <option value="{{ \App\Constants\InvoiceStatus::CANCEL }}" {{ $invoice->status == \App\Constants\InvoiceStatus::CANCEL ? 'selected' : null }}>Hủy bỏ</option>
                    </select>
                    <button type="submit" class="btn btn-primary text-nowrap ml-1"><i class="fas fa-save"></i> Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')

@endsection
