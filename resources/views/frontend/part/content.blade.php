@extends('frontend.part.master')

@section('content')
<div class="learningLesson__text entry-detail">
    {!! $data->content !!}
</div>
@endsection

@section('footer')
    <script>
        $('.entry-detail img').css('height', 'auto');
    </script>
@endsection
