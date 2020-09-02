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
            </ol>
        </nav>
        <h1 class="page-title__title">Giỏ hàng của tôi</h1>
    </div>
</section>

<section class="section" id="cartbox__full">
    <div class="container">
        <div class="cart-text-info entry-detail">
            <h5>Lưu ý</h5>
            <p>Đối với sản phẩm là <b>Khoá học online</b> học trực tiếp không hỗ trợ vận chuyển</p>
            <p>Đối với sản phẩm là <b>Sách</b> sẽ có phí vận chuyển tuỳ theo khu vực địa chị nhận hàng của học viên</p>
            <p>Biểu giá vận chuyển</p>
            <ul>
                <li>Đến trực tiếp trung tâm lấy sách: 0đ</li>
                <li>Nội thành: 20.000đ</li>
                <li>Ngoại thành: 30.000đ - 50.000đ</li>
                <li>Vùng sâu, vùng xa, hải đảo: > 50.000đ</li>
            </ul>
            <p>Số tiền vận chuyển sẽ được cộng trực tiếp vào giá của sản phẩm</p>
        </div>
        <div class="cart-detail">
            <div class="table-responsive">
                <table>
                    <thead class="text-center">
                        <th>#</th>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Tổng tiền</th>
                        <th>Xóa</th>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in inputData" :key="item.id">
                            <td>@{{ index + 1 }}</td>
                            <td>
                                <div class="f-info">
                                    <img class="f-info__img" :src="item.object.thumbnail">
                                    <div class="f-info__body">
                                        <h4 class="f-info__title">@{{ item.object.title }}</h4>
                                        <span v-if="item.object.category" class="f-info__type">Thể loại: <b>@{{ item.object.category.title }}</b></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div v-if="item.__enabled_change_amount" class="f-quantity">
                                    <input type="number" class="form-control" v-model="item.amount" min="1">
                                    <span>
                                        x <b>@{{ currency(item.price) }}</b>
                                    </span>
                                </div>
                                <div v-else class="f-quantity">
                                    <span>
                                        <b>@{{ currency(item.price) }}</b>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="f-price">@{{ currency(item.amount * item.price) }}</span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn--sm" @click="removeCartItem(item.id)"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">Mã giảm giá</td>
                            <td colspan="2">
                                <input type="text" class="form-control">
                                <button type="button" class="btn btn--secondary">Áp dụng</button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">Tổng tiền</td>
                            <td colspan="2">
                                <p class="f-totalPrice">@{{ currency(inputData.reduce((t, i) => t + i.amount * i.price, 0)) }}</p>
                            </td>
                        </tr>
                    </tfoot>
                    </tbody>
                </table>
            </div>

            <div class="cart-detail__btn">
                <button type="button" class="btn" @click="submitCart" :disabled="loading">Thanh toán</button>
            </div>
        </div>
    </div>
</section>

@endsection

@section('footer')
<script>
    new Vue({
        el: '#cartbox__full',
        data: {
            data: [],
            inputData: [],
            loading: false,
        },
        mounted() {
            this.getData();
        },
        methods: {
            getData() {
                this.loading = true;
                axios.get("{{ route('cart.get_data') }}").then(res => {
                    this.data = res;
                    this.inputData = _.cloneDeep(this.data);
                }).then(() => {
                    this.loading = false;
                });
            },
            removeCartItem(id) {
                this.loading = true;
                axios.post("{{ route('cart.delete') }}", {
                    id
                }).then(() => {
                    // nothing
                }).then(() => {
                    this.getData();
                });
            },
            currency(x) {
                return currency(x);
            },
            submitCart() {
                this.loading = true;
                axios.post('{{ route("cart.submit") }}', {
                    cart: this.inputData
                }).then(() => {
                    location.href = '{{ route("cart.confirm") }}';
                }).catch(() => {
                    this.loading = false;
                });
            },
        },
    });

</script>
@endsection
