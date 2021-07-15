@extends('frontend.part.master')

@section('content')
<div class="learningLesson__text entry-detail" id="content">
    {!! $data->content !!}
    <div class="form-group">
        <label>Trả lời:</label>
        <textarea name="content" class="editor" v-model="content"></textarea>
        <button id="btn-submit-sendmail" type="button" class="btn" @click="submit" :class="{ 'd-none': submited }">Nộp bài</button>
    </div>
    <div id="notify">

    </div>
</div>
@endsection

@section('part_script')
    <script>
        $('.entry-detail img').css('height', 'auto');
    </script>
    <script>
        new Vue({
            el: '#content',
            data: {
                submited: false,
                content: ""
            },
            methods: {
                submit() {
                    if (this.content) {

                        bootbox.confirm({
                            message: '<h1>Xác nhận</h1>Bạn chắc chắn muốn nộp bài viết này cho cô giáo?',
                            buttons: {
                                confirm: {
                                    label: 'Xác nhận',
                                    className: 'btn--sm btn--success'
                                },
                                cancel: {
                                    label: 'Hủy bỏ',
                                    className: 'btn--sm bg-dark'
                                }
                            },
                            callback: r => {
                                if (!r) return;
                                this.submited = true;
                                axios.post('/gui-bai-viet/{{ $data->part->id }}', {
                                    content: this.content
                                })
                                .then(function (response) {
                                    bootbox.alert('<h1>Nộp bài thành công!</h1><br/>Bạn vui lòng theo dõi Email và Group kín trên Facebook để nhận kết quả của cô.');
                                    $('#notify').html('<p style="color: #77af41">Nộp bài thành công! Bạn vui lòng theo dõi Email và Group kín trên Facebook để nhận kết quả của cô.</p>');
                                    axios.post("{{ route('part.set_complete') }}", { part_id: {{$part->id}} })
                                    .then(function (response) {
                                        console.log(response);
                                    })
                                    .catch(function (error) {
                                        console.log(error);
                                    })
                                })
                                .catch(function (error) {
                                    $('#notify').html('<p style="color: #e71d36">Gửi bài viết cho giảng viên không thành công. Vui lòng thử lại sau.</p>');
                                });
                            }
                        });
                    } else {
                        bootbox.alert('<h1>Thông báo!</h1><br/>Câu trả lời còn trống. Bạn vui lòng nhập câu trả lời');
                    }
                },
            },
            watch: {
                content: function (val) {
                    if (val) {
                        this.loading = false
                    } else {
                        this.loading = true
                    }
                }
            }
        });
    </script>
@endsection
