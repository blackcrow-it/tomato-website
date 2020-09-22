@extends('frontend.user.master')

@section('header')
<title>Nạp tiền vào tài khoản</title>
@endsection

@section('content')
<h2 class="user-page__title">Nạp tiền</h2>

<div class="user-page__moneyCard" id="recharge">
    <div class="tabJs">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tab-naptien" role="tab" aria-controls="tab-naptien" aria-selected="true">Nạp tiền</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-lichsunap" role="tab" aria-controls="tab-lichsunap" aria-selected="false">Lịch sử nạp tiền</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tab-naptien" role="tabpanel">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $msg)
                                <li>{{ $msg }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
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
                <div class="form-moneyCard">
                    <div class="form-moneyCard__header">
                        <h3 class="f-title">Nhập số tiền bạn muốn nạp vào ví Tomato Online</h3>
                        <p class="f-subtitle">Số tiền bạn sẽ nạp: <b>@{{ currency(money) }}</b></p>
                    </div>
                    <div class="input-item form-moneyCard__input">
                        <div class="input-item__inner">
                            <input type="text" v-model="money" placeholder="Nhập số tiền bạn muốn nạp" class="form-control" autocomplete="off" :disabled="loading">
                            <span>VND</span>
                        </div>
                        <p v-for="msg in validateErrors.money" class="error">@{{ msg }}</p>
                    </div>
                    <div class="form-moneyCard__btn">
                        <div class="row">
                            <div class="col-md-6">
                                <form class="form-momo">
                                    <button type="button" :disabled="loading" @click="requestMomo">
                                        <img src="{{ asset('tomato/assets/img/icon/icon-momo.jpg') }}">
                                        <p>
                                            Thanh toán qua Ví MoMo
                                            <em>Thanh toán thông qua ví điện tử MoMo</em>
                                        </p>
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <form class="form-epay">
                                    <button type="button" :disabled="loading" @click="requestEpay">
                                        <img src="{{ asset('tomato/assets/img/icon/icon-epay.png') }}">
                                        <p>
                                            Thanh toán qua Epay
                                            <em>Internet banking và Visa Mastercard</em>
                                        </p>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="text-center pt-10">
                            <a href="huongdannaptien.html" class="btn-link">Hướng dẫn chi tiết nạp tiền <i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                    <div class="form-moneyCard__text">
                        <div class="entry-detail">
                            <h3 class="text-center">Hoặc chuyển khoản trực tiếp</h3>
                            <br>
                            <h6>Cách 1 : Nộp tiền trực tiếp qua tài khoản ngân hàng !</h6>
                            <p>Khi nộp tiền ghi rõ trong phần nội dung chuyển tiền: Email + SĐT (Vd: nguyena@gmail.com + 0987654321).<br><small>( Sau khi chuyển khoản nhắn tin vào số máy 0965.113.913 thông báo số tiền đã thanh toán, số điện thoại và địa chỉ email)</small></p>
                            <p><b>NGÂN HÀNG HỖ TRỢ THANH TOÁN</b></p>
                            <ul>
                                <li>Ngân hàng TMCP Đầu tư và Phát triển Việt Nam (BIDV)</li>
                                <li>Số tài khoản: <b>32810000813534</b></li>
                                <li>Chủ TK: CÔNG TY CỔ PHẦN TƯ VẤN VÀ ĐÀO TẠO TOMATO<br>Ngân hàng BIDV chi nhánh Lạch Tray, Hải Phòng.</li>
                            </ul>
                            <br>
                            <h6>Cách 2: Thanh toán qua cổng thanh toán</h6>
                            <ul>
                                <li>ZaloPay/MoMo - <b>0942769666</b> VU QUOC CONG</li>
                                <li>ViettelPay - <b>9704229246814730</b> Chủ TK VU QUOC CONG</li>
                            </ul>
                            <br>
                            <h6>Cách 3: Đến trực tiếp để nộp phí tại cơ sở</h6>
                            <ul>
                                <li>Cơ sở 1: 300 Lạch Tray, Lê Chân, Hải Phòng</li>
                                <li>Cơ sở 2: 65 Quán Nam, Lê Chân, Hải Phòng</li>
                            </ul>
                            <br>

                            <h4>Trung tâm Ngoại ngữ - Tin học TOMATO chúc bạn Thành Công</h4>
                            <p>Mọi thông tin chi tiết xin liên hệ HOTLINE: <a href="#">0965 113 913</a> Mr Nam</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" role="tabpanel" id="tab-lichsunap">
                <div class="table-responsive table-historyCard">
                    <table>
                        <thead>
                            <th>Stt</th>
                            <th>Tài khoản</th>
                            <th>Số tiền</th>
                            <th>Loại thanh toán</th>
                            <th>Thời gian</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td class="f-name">Nguyễn Quốc Khánh</td>
                                <td class="f-price">500.000đ</td>
                                <td class="f-card">Momo</td>
                                <td class="f-date">12-05-2020</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td class="f-name">Nguyễn Quốc Khánh</td>
                                <td class="f-price">500.000đ</td>
                                <td class="f-card">Epay</td>
                                <td class="f-date">12-05-2020</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td class="f-name">Nguyễn Quốc Khánh</td>
                                <td class="f-price">500.000đ</td>
                                <td class="f-card">Trực tiêp</td>
                                <td class="f-date">12-05-2020</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <nav class="pagination text-center">
                    <ul class="pagination__list">
                        <li><a href="#"><i class="fa fa-angle-double-left"></i></a></li>
                        <li><a href="#">1</a></li>
                        <li class="current"><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li><a href="#"><i class="fa fa-angle-double-right"></i></a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection

@section('user_script')
<script>
    new Vue({
        el: '#recharge',
        data: {
            money: 500000,
            loading: false,
            validateErrors: {},
        },
        methods: {
            currency(x) {
                return currency(x, 0);
            },
            requestMomo() {
                this.loading = true;
                axios.post("{{ route('recharge.momo.request') }}", {
                    money: this.money
                }).then(res => {
                    window.location.href = res.pay_url;
                }).catch(res => {
                    this.validateErrors = res.errors;
                }).then(() => {
                    this.loading = false;
                });
            },
            requestEpay() {

            },
        },
    });

</script>
@endsection
