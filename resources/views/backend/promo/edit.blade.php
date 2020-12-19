@extends('backend.master')

@section('title')
@if(request()->routeIs('admin.promo.add'))
    Thêm mã khuyến mãi mới
@else
    Sửa mã khuyến mãi
@endif
@endsection

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">
            @if(request()->routeIs('admin.promo.add'))
                Thêm mã khuyến mãi mới
            @else
                Sửa mã khuyến mãi
            @endif
        </h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.promo.list') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
        </div>
    </div><!-- /.col -->
</div>
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

<div class="card" id="promo">
    <div class="card-body">
        <div class="form-group">
            <label>Mã khuyến mãi</label>
            <input type="text" v-model="promo.code" placeholder="Mã khuyến mãi" class="form-control @error('promo.code') is-invalid @enderror">
            @error('promo.code')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group">
            <label>Loại</label>
            <div>
                <div class="form-check-inline">
                    <input type="radio" v-model="promo.type" class="form-check-input @error('promo.type') is-invalid @enderror" id="cr-type-1" value="{{ \App\Constants\PromoType::DISCOUNT }}">
                    <label class="form-check-label" for="cr-type-1">Giảm giá</label>
                </div>
                <div class="form-check-inline">
                    <input type="radio" v-model="promo.type" class="form-check-input @error('promo.type') is-invalid @enderror" id="cr-type-2" value="{{ \App\Constants\PromoType::SAME_PRICE }}">
                    <label class="form-check-label" for="cr-type-2">Đồng giá</label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Giá trị</label>
            <div>
                <currency-input v-model="promo.value" class="form-control" />
            </div>
            @error('promo.value')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group">
            <label>Thời gian kết thúc</label>
            <div>
                <datetimepicker v-model="promo.expires_on" format="YYYY-MM-DD hh:mm" formatted="YYYY-MM-DD hh:mm" :no-label="true" />
            </div>
            @error('promo.expires_on')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group">
            <label>Loại</label>
            <div>
                <div class="form-check-inline">
                    <input type="radio" v-model="promo.used_many_times" class="form-check-input @error('promo.used_many_times') is-invalid @enderror" id="cr-type-many-users-1" :value="true">
                    <label class="form-check-label" for="cr-type-many-users-1">Dùng nhiều lần</label>
                </div>
                <div class="form-check-inline">
                    <input type="radio" v-model="promo.used_many_times" class="form-check-input @error('promo.used_many_times') is-invalid @enderror" id="cr-type-many-users-2" :value="false">
                    <label class="form-check-label" for="cr-type-many-users-2">Dùng một lần</label>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="button" class="btn btn-primary" @click="submit"><i class="fas fa-save"></i> Lưu</button>
    </div>
</div>
@endsection

@section('script')
<script>
    new Vue({
        el: '#promo',
        data: {
            promo: {
                code: undefined,
                type: '{{ \App\Constants\PromoType::DISCOUNT }}',
                value: undefined,
                expires_on: undefined,
                used_many_times: true
            }
        },
        mounted() {
            this.getItem();
        },
        methods: {
            submit() {
                redirectPost(location.href, {
                    promo: this.promo
                });
            },
            getItem() {
                axios.get("{{ route('admin.promo.get_item', [ 'id' => $promo->id ?? 0 ]) }}").then(res => {
                    if (!res) return;
                    this.promo = res;
                });
            }
        }
    });
</script>
@endsection
