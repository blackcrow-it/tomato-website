@component('mail::message')
Họ và tên: **{{ $data['name_student'] }}**\
Email: **{{ $data['email_student'] }}**\
Số điện thoại: **{{ $data['phone_student'] }}**
* Khoá học: {{ $data['course']->title }}
* Bài học: {{ $data['lesson']->title }}
* Đầu mục: {{ $data['part']->title }}

# Nội dung bài viết
***
{{ $data['part_content'] }}
***

# Bài làm của học viên
***
{{ $data['content'] }}
***
### Thời gian nộp bài: {{ now()->format('d-m-Y H:i') }}

*Vui lòng không trả lời email này. Giáo viên chữa bài rồi gửi qua email của học viên và đăng bài lên group kín Facebook*
@endcomponent
