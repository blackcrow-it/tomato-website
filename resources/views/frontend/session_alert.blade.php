@if($errors->any())
    <div class="alert alert-danger">
        @if($errors->count() > 1)
            <ul class="mb-0">
                @foreach($errors->all() as $msg)
                    <li>{!! $msg !!}</li>
                @endforeach
            </ul>
        @else
            {!! $errors->first() !!}
        @endif
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success">
        @if(is_array(session('success')))
            <ul class="mb-0">
                @foreach(session('success') as $msg)
                    <li>{!! $msg !!}</li>
                @endforeach
            </ul>
        @else
            {!! session('success') !!}
        @endif
    </div>
@endif
