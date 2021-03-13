@component('mail::message')

**Tên:** {{ $data['name'] }}\
@if(!empty($data['phone']))
**Số điện thoại:** {{ $data['phone'] }}\
@endif
**Email:** {{ $data['email'] }}\

@foreach (explode("\n", $data['content']) as $text)
    {{ trim($text) }}
@endforeach

*Thời gian: {{ now()->format('d-m-Y H:i') }}*

*Đây là email thông báo tự động, vui lòng không trả lời email này*
@endcomponent
