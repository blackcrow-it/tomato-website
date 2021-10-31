@component('mail::message')
Thân gửi {{ $data['student_name'] }},

Bạn có lớp học online:

Chủ đề: {{ $data['topic'] }}

ID phòng Zoom: {{ $data['id_zoom'] }}

Thời gian bắt đầu: {{ date( "H:i:s d/m/Y", strtotime( $data['start_time'] ) ) }}


@component('mail::button', ['url' => $data['join_url']])
Tham gia lớp học
@endcomponent

Cảm ơn bạn đã lựa chọn Tomato,<br>
{{ config('app.name') }}
@endcomponent
