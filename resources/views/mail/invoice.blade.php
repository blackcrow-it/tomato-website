@component('mail::message')

# Đơn hàng #{{ $data['invoice']->id }}
Tạo lúc: {{ $data['invoice']->created_at->format('d-m-Y H:i:s') }}

# Thông tin người nhận
Họ tên: {{ $data['invoice']->name }}\
Số điện thoại: {{ $data['invoice']->name }}\
Hình thức: {{ $data['invoice']->shipping ? 'Giao hàng' : 'Nhận hàng tại trung tâm' }}

@if($data['invoice']->shipping)
Địa chỉ: {{ $data['invoice']->address }}\
Quận, huyện: {{ $data['invoice']->district }}\
Tỉnh, thành phố: {{ $data['invoice']->city }}
@endif

# Thành viên đặt hàng
Tài khoản: {{ $data['invoice']->user->username }}\
Họ tên: {{ $data['invoice']->user->name }}\
Số điện thoại: {{ $data['invoice']->user->phone }}\
Địa chỉ: {{ $data['invoice']->user->address }}\
Số dư: {{ currency($data['invoice']->user->money, '0 ₫') }}

# Chi tiết đơn hàng

@component('mail::table')
| Loại | Tiêu đề | Số lượng | Đơn giá | Thành tiền |
| ---- | ------- | --------:| -------:| ----------:|
<?php
    $total = 0;
    foreach ($data['invoice']->items as $item) {
        $subTotal = $item->price * $item->amount;
        $total += $subTotal;

        $row = [];

        switch ($item->type) {
            case \App\Constants\ObjectType::COURSE:
                $row[] = 'Khóa học';
                $row[] = '[' . $item->course->title . '](' . $item->course->url . ')';
                break;
            case \App\Constants\ObjectType::BOOK:
                $row[] = 'Sách';
                $row[] = '[' . $item->book->title . '](' . $item->book->url . ')';
                break;
        }
        $row[] = $item->amount;
        $row[] = currency($item->price);
        $row[] = currency($subTotal);

        echo '| ' . implode(' | ', $row) . ' |';
    }
?>
| | | | Phí vận chuyển | {{ currency(0) }}             |
| | | | Tổng cộng      | {{ currency($total, '0 ₫') }} |
@endcomponent

@component('mail::button', ['url' => route('admin.invoice.detail', [ 'id' => $data['invoice']->id ])])
Xem chi tiết
@endcomponent

*Đây là email thông báo tự động, vui lòng không trả lời email này*
@endcomponent
