@extends('frontend.part.master')

@section('content')
<div class="learningLesson__text entry-detail" id="content">
    {!! $data->content !!}
    <div class="form-group">
        <label>Trả lời:</label>
        <textarea name="content" class="editor" v-model="content"></textarea>
        <button type="button" class="btn" @click="submit" :class="{ 'd-none': submited }" :disable="loading">Nộp bài</button>
    </div>
    <div id="notify">

    </div>
</div>
@endsection

@section('footer')
    <script>
        $('.entry-detail img').css('height', 'auto');
    </script>
    <script>
        new Vue({
            el: '#content',
            data: {
                submited: false,
                loading: false,
                content: ""
            },
            methods: {
                submit() {
                    this.loading = true;
                    this.submited = true;
                    axios.post('/gui-bai-viet/{{ $data->part->id }}', {
                        content: this.content
                    })
                    .then(function (response) {
                        console.log(response);
                        $('#notify').html('<p style="color: #77af41;">Đã gửi bài viết cho giáo viên. Học viên vui lòng theo dõi email và group kín trên Facebook.</p>');
                    })
                    .catch(function (error) {
                        console.log(error)
                        $('#notify').html('<p style="color: #e71d36">Gửi bài viết cho giảng viên không thành công. Vui lòng thử lại sau.</p>');
                    });
                },
            },
        });
    </script>
@endsection
