@component('mail::message')

**Tên:** {{ $data['name'] }}\
**Số điện thoại:** {{ $data['phone'] }}\
**Email:** {{ $data['email'] }}\
**Khóa học quan tâm:** {{ $data['course'] ?? null }}

@component('mail::panel')
@foreach (explode("\n", $data['content']) as $text)
    {{ trim($text) }}<br>
@endforeach
@endcomponent

*Thời gian: {{ now()->format('d-m-Y H:i') }}*

*Đây là email thông báo tự động, vui lòng không trả lời email này*
@endcomponent
