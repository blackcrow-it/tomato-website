@component('mail::message')

@component('mail::panel')
Mã giao dịch: {{ $data['request_id'] ?? 'NULL' }}
@endcomponent

# Giao dịch nạp tiền qua MoMo thành công

Tài khoản: **{{ $data['user']->username }}**

@component('mail::table')
|               |                                                            |
| ------------- | ----------------------------------------------------------:|
| Số dư cũ      | **{{ currency($data['user']->money, '0 ₫') }}**            |
| Nạp thêm      | **{{ currency($data['amount']) }}**                        |
| Số dư mới     | **{{ currency($data['user']->money + $data['amount']) }}** |
@endcomponent

*Đây là email thông báo tự động, vui lòng không trả lời email này*
@endcomponent
