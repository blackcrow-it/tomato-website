@extends('backend.master')

@section('title')
Cài đặt trang chủ
@endsection

@section('content')
@if($errors->any())
    <div class="callout callout-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $msg)
                <li>{{ $msg }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="callout callout-success">
        @if(is_array(session('success')))
            <ul class="mb-0">
                @foreach(session('success') as $msg)
                    <li>{{ $msg }}</li>
                @endforeach
            </ul>
        @else
            {{ session('success') }}
        @endif
    </div>
@endif

<div class="card" id="settings">
    <form action="{{ route('admin.setting.submit') }}" method="POST">
        @csrf
        <div class="card-header">
            <h3>Cài đặt trang chủ</h3>
        </div>
        <div class="card-body">
            @yield('setting_content')
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu</button>
        </div>
    </form>
</div>
@endsection

@section('script')
<script>
    new Vue({
        el: '#settings',
        data: {
            settings: {},
        },
        methods: {
            getValue(key, defaultValue) {
                return this.settings[key] || defaultValue;
            },
            uploadImage(key) {
                const input = $('<input type="file">');
                $(input).on('change', e => {
                    const files = $(e.target).prop('files');
                    if (files.length == 0) return;

                    const formData = new FormData();
                    formData.append('image', files[0]);
                    formData.append('key', key);

                    this.settings[key] = '{{ asset("images/progress.gif") }}';
                    this.$forceUpdate();

                    axios.post('{{ route("admin.setting.upload_image") }}', formData).then(res => {
                        this.settings[key] = res.src;
                        this.$forceUpdate();
                    }).catch(() => {
                        this.settings[key] = undefined;
                        this.$forceUpdate();
                    });
                });
                $(input).trigger('click');
            }
        },
    });

</script>
@endsection
