@extends('frontend.part.master')

@section('content')
<div class="learningLesson__text entry-detail">
    {!! $data->content !!}
    <div class="form-group">
        <label>Trả lời:</label>
        <textarea name="content" class="editor"></textarea>
    </div>
</div>
@endsection

@section('footer')
    <script>
        $('.entry-detail img').css('height', 'auto');
    </script>
@endsection
