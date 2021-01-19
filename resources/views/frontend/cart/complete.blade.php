@extends('frontend.master')

@section('header')
<title>Giỏ hàng</title>
@endsection

@section('body')
<section class="section page-title">
    <div class="container">
        <nav class="breadcrumb-nav">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cart') }}">Giỏ hàng</a></li>
            </ol>
        </nav>
        <h1 class="page-title__title">Thanh toán thành công</h1>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 offset-xl-2">
                <div class="payment-success">
                    <span class="f-icon"><img src="{{ asset('tomato/assets/img/icon/icon-pay-success.png') }}"></span>
                    <div class="f-title">Đơn hàng của bạn đã được thanh toán thành công.</div>
                    <p class="f-text">Cảm ơn bạn đã tin tưởng, sử dụng dịch vụ của <b>Tomato Online</b></p>

                    <div>
                        <a href="{{ route('home') }}" class="btn btn--secondary">Quay lại trang chủ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('footer')
<script>
    new Vue({
        el: '#cartbox__confirm',
        data: {
            data: [],
            loading: false,
            totalPrice: 0,
        },
        mounted() {
            this.getData();
        },
        methods: {
            getData() {
                this.loading = true;
                axios.get("{{ route('cart.get_data') }}").then(res => {
                    this.data = res;

                    this.totalPrice = this.data.reduce((total, item) => {
                        return total + item.price * item.amount;
                    }, 0);
                }).then(() => {
                    this.loading = false;
                });
            },
            currency(x) {
                return currency(x);
            },
        },
    });

</script>
@endsection
