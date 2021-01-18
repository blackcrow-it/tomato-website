@component('mail::message')

# Số tiền của thành viên đã được thay đổi thành công

Tài khoản: **{{ $data['user']->username }}**

@component('mail::table')
|               |                                                            |
| ------------- | ----------------------------------------------------------:|
| Số dư cũ      | **{{ currency($data['old_money'], '0 ₫') }}**            |
| Thay đổi      | **{{ currency($data['amount']) }}**                        |
| Số dư mới     | **{{ currency($data['user']->money) }}** |
@endcomponent

*Đây là email thông báo tự động, vui lòng không trả lời email này*
@endcomponent
