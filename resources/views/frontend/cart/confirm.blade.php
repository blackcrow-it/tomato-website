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
        <h1 class="page-title__title">Xác nhận thanh toán</h1>
    </div>
</section>

<section class="section" id="cartbox__confirm">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 offset-xl-2">
                <div class="payment-confirmation">
                    <div class="payment-confirmation__inner">
                        <div class="payment-confirmation__header">
                            <p class="f-user">Tài khoản đặt: <b>{{ auth()->user()->username }}</b></p>
                            <p class="f-price">Số tiền thanh toán: <b>@{{ currency(totalPrice) }}</b></p>
                            <p class="f-subtitle">Chi tiết đơn hàng:</p>
                        </div>
                        <div class="payment-confirmatio__list">
                            <div v-for="(item, index) in data" :key="item.id" class="f-list__item">
                                <div class="f-info">
                                    <img class="f-info__img" :src="item.object.thumbnail">
                                    <div class="f-info__body">
                                        <h4 class="f-info__title">@{{ item.object.title }}</h4>
                                        <ul class="f-info__list">
                                            <li v-if="item.object.category">Thể loại: <b>@{{ item.object.category.title }}</b></li>
                                            <li>Số lượng: <b>@{{ item.amount }} x @{{ currency(item.price) }}</b></li>
                                        </ul>
                                        <p class="f-price">Tổng: <b>@{{ currency(item.amount * item.price) }}</b></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="payment-confirmation__footer text-center">
                        <form action="{{ route('cart.complete') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn">Thanh toán</button>
                        </form>
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
