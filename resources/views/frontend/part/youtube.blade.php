@extends('frontend.part.master')

@section('content')
<div class="learningLesson__video">
    <div class="product-detail__img">
        <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{ $data->youtube_id ?? null }}" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>
</div>
@endsection
