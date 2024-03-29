@extends('backend.master')

@section('title')
@yield('setting_title')
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
            <h3>
                @yield('setting_title')
            </h3>
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
            local: [],
            province: '{{ config('settings.province_shipment') }}',
            district: '{{ config('settings.district_shipment') }}',
            @if (config('settings.bio_item'))
            bioItems: {!! html_entity_decode(config('settings.bio_item'), ENT_QUOTES, 'UTF-8') !!}
            @else
            bioItems: []
            @endif
        },
        mounted() {
            console.log(this.bioItems);
            this.getLocalData();
        },
        methods: {
            getValue(key, defaultValue) {
                return this.settings[key] || defaultValue;
            },
            uploadImage(key) {
                console.log(key);
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
            },
            uploadImageIconBio(index) {
                const input = $('<input type="file">');
                $(input).on('change', e => {
                    const files = $(e.target).prop('files');
                    if (files.length == 0) return;

                    const formData = new FormData();
                    formData.append('image', files[0]);
                    formData.append('key', 'bio-icon-' + index);

                    this.bioItems[index].linkIcon = '{{ asset("images/progress.gif") }}';
                    this.$forceUpdate();

                    axios.post('{{ route("admin.setting.upload_image") }}', formData).then(res => {
                        this.bioItems[index].linkIcon = res.src;
                        this.$forceUpdate();
                    }).catch(() => {
                        this.bioItems[index].linkIcon = undefined;
                        this.$forceUpdate();
                    });
                });
                $(input).trigger('click');
            },
            getLocalData() {
                axios.get('{{ url("json/vietnam-db.json") }}').then(res => {
                    res.sort((a, b) => a.name.localeCompare(b.name));
                    this.local = res;
                });
            },
            addLinkBio() {
                this.bioItems.push({'linkIcon': null, 'title': null, 'link': null, 'type': 'link'});
            },
            deleteLinkBio(index) {
                this.bioItems.splice(index, 1);
            }
        },
        watch: {
            bioItems: {
                handler: function (val, oldVal) {
                    document.getElementById("bio-item").value = JSON.stringify(val);
                },
                deep: true
            }
        }
    });

</script>
@yield('setting_script')
@endsection
