@extends('frontend.user.master')

@section('header')
<title>Thông tin cá nhân</title>
@endsection

@section('content')

<h2 class="user-page__title">Lịch sử mua hàng</h2>

<div class="user-page__productHistory">
    <div class="table-responsive">
        <table>
            <thead>
                <th>Stt</th>
                <th>Sản phẩm</th>
                <th>Giá</th>
                <th>Thời gian</th>
            </thead>
            <tbody>
                @foreach($invoice_items as $item)
                    <tr>
                        <td>{{ ($invoice_items->currentPage() - 1) * $invoice_items->perPage() + $loop->iteration }}</td>
                        <td>
                            <div class="f-product">
                                <img class="f-product__img" src="{{ $item->object->thumbnail }}">
                                <h3 class="f-product__name"><a href="{{ $item->object->url }}" target="blank">{{ $item->object->title }}</a></h3>
                                <ul class="f-product__info">
                                    <li>Số lượng: <span>{{ $item->amount }}</span></li>
                                    @if($item->object->category)
                                        <li>Phân loại: <span>{{ $item->object->category->title }}</span></li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                        <td>
                            <div class="f-price">
                                {{ currency($item->price) }}
                            </div>
                        </td>
                        <td>{{ $item->created_at->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $invoice_items->links() }}
</div>
</div>
@endsection
