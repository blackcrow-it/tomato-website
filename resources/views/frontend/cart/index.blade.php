@extends('frontend.master')

@section('header')
<title>Giỏ hàng</title>
@endsection

@section('body')
<div id="cartbox__full">
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

<section class="section">
    <div class="container">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $msg)
                        <li>{{ $msg }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div v-if="showShipInfo" class="cart-text-info entry-detail mb-3">
            <div class="h3">Lưu ý</div>
            <div class="">
                <p>Đối với sản phẩm là <b>Khoá học online</b> học trực tiếp không hỗ trợ vận chuyển</p>
                <p>Đối với sản phẩm là <b>Sách</b> sẽ có phí vận chuyển tuỳ theo khu vực địa chị nhận hàng của học viên</p>
                <p>Biểu giá vận chuyển</p>
                <ul>
                    <li>Đến trực tiếp trung tâm lấy sách: 0đ</li>
                    <li>Nội thành & Ngoại thành: 16.500đ - 30.000đ</li>
                    <li>Nội miền (Miền Bắc): 30.000đ - 35.000đ</li>
                    <li>Liên miền (Miền Trung, Miền Nam): &gt; 30.000đ</li>
                </ul>
                <p>Số tiền vận chuyển sẽ được cộng trực tiếp vào giá của sản phẩm</p>
            </div>
        </div>
        <div class="cart-detail">
            <div class="table-responsive">
                <table>
                    <thead class="text-center">
                        <th>#</th>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Tổng tiền</th>
                        <th>Xóa</th>
                    </thead>
                    <tbody>
                        <tr v-if="inputData.length == 0" class="bg-light">
                            <td colspan="99" class="text-left">
                                Bạn chưa có sản phẩm nào trong giỏ hàng
                            </td>
                        </tr>
                        <tr v-if="inputData.filter(x => x.type == '{{ \App\Constants\ObjectType::COMBO_COURSE }}').length > 0" class="bg-light">
                            <td colspan="99" class="text-left">
                                <b>Combo khóa học online</b><br>
                                <small class="text-danger">* Khoá học online trực tiếp trên website tomatoonline.edu.vn, không có hình thức vận chuyển.</small>
                            </td>
                        </tr>
                        <tr v-for="(item, index) in inputData.filter(x => x.type == '{{ \App\Constants\ObjectType::COMBO_COURSE }}')" :key="item.id">
                            <td>@{{ index + 1 }}</td>
                            <td>
                                <div class="f-info">
                                    <img class="f-info__img" :src="item.object.thumbnail">
                                    <div class="f-info__body">
                                        <div class="f-info__title">@{{ item.object.title }}</div>
                                        <div v-if="item.object.category" class="f-info__type">Thể loại: <b>@{{ item.object.category.title }}</b></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="f-quantity text-center">
                                    <span><b>@{{ currency(item.price) }}</b></span>
                                    <template v-if="item.del_price">
                                        <br>
                                        <small><del>@{{ currency(item.del_price) }}</del></small>
                                    </template>
                                </div>
                            </td>
                            <td>
                                <div class="f-quantity text-center">
                                    <span><b>1</b></span>
                                </div>
                            </td>
                            <td>
                                <span class="f-price">@{{ currency(item.amount * item.price) }}</span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn--sm" @click="removeCartItem(item.id)"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr v-if="inputData.filter(x => x.type == '{{ \App\Constants\ObjectType::COURSE }}').length > 0" class="bg-light">
                            <td colspan="99" class="text-left">
                                <b>Khóa học online</b><br>
                                <small class="text-danger">* Khoá học online trực tiếp trên website tomatoonline.edu.vn, không có hình thức vận chuyển.</small>
                            </td>
                        </tr>
                        <tr v-for="(item, index) in inputData.filter(x => x.type == '{{ \App\Constants\ObjectType::COURSE }}')" :key="item.id">
                            <td>@{{ index + 1 }}</td>
                            <td>
                                <div class="f-info">
                                    <img class="f-info__img" :src="item.object.thumbnail">
                                    <div class="f-info__body">
                                        <div class="f-info__title">@{{ item.object.title }}</div>
                                        <div v-if="item.object.category" class="f-info__type">Thể loại: <b>@{{ item.object.category.title }}</b></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="f-quantity text-center">
                                    <span><b>@{{ currency(item.price) }}</b></span>
                                    <template v-if="item.del_price">
                                        <br>
                                        <small><del>@{{ currency(item.del_price) }}</del></small>
                                    </template>
                                </div>
                            </td>
                            <td>
                                <div class="f-quantity text-center">
                                    <span><b>1</b></span>
                                </div>
                            </td>
                            <td>
                                <span class="f-price">@{{ currency(item.amount * item.price) }}</span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn--sm" @click="removeCartItem(item.id)"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr v-if="inputData.filter(x => x.type == '{{ \App\Constants\ObjectType::BOOK }}').length > 0" class="bg-light">
                            <td colspan="99" class="text-left">
                                <b>Tài liệu</b>
                            </td>
                        </tr>
                        <tr v-for="(item, index) in inputData.filter(x => x.type == '{{ \App\Constants\ObjectType::BOOK }}')" :key="item.id">
                            <td>@{{ index + 1 }}</td>
                            <td>
                                <div class="f-info">
                                    <img class="f-info__img" :src="item.object.thumbnail">
                                    <div class="f-info__body">
                                        <div class="f-info__title">@{{ item.object.title }}</div>
                                        <div v-if="item.object.category" class="f-info__type">Thể loại: <b>@{{ item.object.category.title }}</b></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="f-quantity text-center">
                                    <span><b>@{{ currency(item.price) }}</b></span>
                                    <template v-if="item.del_price">
                                        <br>
                                        <small><del>@{{ currency(item.del_price) }}</del></small>
                                    </template>
                                </div>
                            </td>
                            <td>
                                <div class="f-quantity text-center">
                                    <input type="number" class="form-control" v-model="item.amount" min="1" v-on:keyup="changeAmountBook($event)">
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
                        <tr v-if="inputData.filter(x => x.type == '{{ \App\Constants\ObjectType::COURSE }}').length > 0">
                            <td colspan="4">
                                <b>Mã giảm giá</b><br>
                                <small class="text-danger">* Mã giảm giá chỉ áp dụng dành cho mua khoá học lẻ.</small>
                            </td>
                            <td colspan="2">
                                <div class="form-discount" v-if="promoData === undefined">
                                    <input type="text" v-model="promoCode" class="form-control">
                                    <button type="button" class="btn btn--secondary" :disabled="promoCode === undefined" @click="getPromo">Áp dụng</button>
                                </div>
                                <div v-else class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-primary font-weight-bold">@{{ promoCode }}</div>
                                        <div v-if="promoData.type == '{{ \App\Constants\PromoType::DISCOUNT }}'">
                                            Giảm giá @{{ promoData.value }}%
                                        </div>
                                        <div v-if="promoData.type == '{{ \App\Constants\PromoType::SAME_PRICE }}'">
                                            Đồng giá @{{ currency(promoData.value) }}
                                        </div>
                                    </div>
                                    <div>
                                        <button type="button" title="Xóa mã giảm giá" @click="clearPromo">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="inputData.filter(x => x.type == '{{ \App\Constants\ObjectType::BOOK }}').length > 0">
                            <td colspan="4">Phí vận chuyển </td>
                            <td colspan="2">
                                <div id="transport-id-1">
                                    <div v-if="shipInfo.shipping">
                                        <p v-if="shipmentFee && !isFast"><b>+@{{ currency(shipmentFee) }}</b></p>
                                        <p v-if="shipmentFeeFast && isFast"><b>+@{{ currency(shipmentFeeFast) }}</b></p>
                                        <p v-if="!(shipmentFee && shipmentFee)">Chưa có dữ liệu</p>
                                    </div>
                                    <div v-else>
                                        <p>Đến trực tiếp trung tâm lấy</p>
                                    </div>
                                    <a href="#" data-toggle="modal" data-target="#form-address-modal" data-id="transport-id-1"><i class="fa fa-pencil"></i> Chỉnh sửa</a>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="inputData.filter(x => x.type == '{{ \App\Constants\ObjectType::BOOK }}').length > 0">
                            <td colspan="4">Phương thức thanh toán</td>
                            <td colspan="2">
                                <div class="input-price">
                                    <div class="checkbox-wrap">
                                        <label class="checkbox-item">
                                            <input type="radio" name="chohinhthuc" v-model="shipCOD" :value="false">
                                            <span class="checkbox-item__check"></span>
                                            <p class="checkbox-item__text">Thanh toán bằng số dư tài khoản
                                                <small v-if="{{ auth()->user()->money }} < totalPrice()">Số dư hiện tại <b style="color: #E71D36;">{{ currency(auth()->user()->money, '0đ') }}</b> không đủ ( <a href="{{ route('user.recharge') }}">nạp tiền</a> )</small>
                                                <small v-else>Số dư hiện tại <b style="color: #E71D36;">{{ currency(auth()->user()->money, '0đ') }}</b> ( <a href="{{ route('user.recharge') }}">nạp thêm</a> )</small>
                                            </p>
                                        </label>
                                        <label class="checkbox-item">
                                            <input type="radio" name="chohinhthuc" v-model="shipCOD" :value="true" :disabled="inputData.filter(x => (x.type == '{{ \App\Constants\ObjectType::COURSE }}' || x.type == '{{ \App\Constants\ObjectType::COMBO_COURSE }}' )).length > 0 || !shipInfo.shipping">
                                            <span class="checkbox-item__check"></span>
                                            <p v-if="inputData.filter(x => (x.type == '{{ \App\Constants\ObjectType::COURSE }}' || x.type == '{{ \App\Constants\ObjectType::COMBO_COURSE }}' )).length > 0 || !shipInfo.shipping" class="checkbox-item__text"><del>Thanh toán khi nhận hàng
                                                <small>Thanh toán tổng số tiền cho shipper</small></del>
                                            </p>
                                            <p v-else class="checkbox-item__text">Thanh toán khi nhận hàng
                                                <small>Thanh toán tổng số tiền cho shipper</small>
                                            </p>
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">Tổng tiền</td>
                            <td colspan="2">
                                <p class="f-totalPrice">@{{ currency(totalPrice()) }}</p>
                            </td>
                        </tr>
                    </tfoot>
                    </tbody>
                </table>
            </div>

            <div class="cart-detail__btn">
                <button type="button" class="btn" @click="submitCart" :disabled="loading || loadingShipmentFee">Thanh toán</button>
            </div>
        </div>
    </div>
</section>
<!-- Modal hiển thị thông tin vận chuyển -->
<div class="modal fade" id="form-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <h3 class="f-title text-center">Chọn hình thức giao hàng</h3>
            <ul class="choose-form">
                <li class="choose-form__item">
                    <label class="checkbox-item">
                        <input type="radio" v-model="shipInfo.shipping" :value="false">
                        <span class="checkbox-item__check"></span>
                        <p class="checkbox-item__text">Đến trực tiếp trung tâm lấy (<b class="f-price">Miễn phí</b>)</p>
                    </label>
                    <div class="choose-form__content" :class="{ 'show': !shipInfo.shipping }">
                        <ul>
                            <li><b>Địa chỉ ĐKKD CS1:</b> {{ config('settings.address1') }}</li>
                            @if(config('settings.address2'))
                                <li><b>Cơ sở 2:</b> {{ config('settings.address2') }}</li>
                            @endif
                            @if(config('settings.address3'))
                                <li><b>Cơ sở 3:</b> {{ config('settings.address3') }}</li>
                            @endif
                            @if(config('settings.address4'))
                                <li><b>Cơ sở 4:</b> {{ config('settings.address4') }}</li>
                            @endif
                            @if(config('settings.address5'))
                                <li><b>Cơ sở 5:</b> {{ config('settings.address5') }}</li>
                            @endif
                            @if(config('settings.address6'))
                                <li><b>Cơ sở 6:</b> {{ config('settings.address6') }}</li>
                            @endif
                            <li><b>Hotline chính:</b> {{ config('settings.hotline1') }}</li>
                            @if(config('settings.hotline2'))
                                <li><b>Điện thoại:</b> {{ config('settings.hotline2') }}</li>
                            @endif
                            @if(config('settings.hotline3'))
                                <li><b>Điện thoại:</b> {{ config('settings.hotline3') }}</li>
                            @endif
                            @if(config('settings.hotline4'))
                                <li><b>Điện thoại:</b> {{ config('settings.hotline4') }}</li>
                            @endif
                            <li><b>Email:</b> {{ config('settings.email') }}</li>
                        </ul>
                    </div>
                </li>
                @if(config('settings.province_shipment') && config('settings.district_shipment'))
                <li class="choose-form__item">
                    <label class="checkbox-item">
                        <input type="radio" v-model="shipInfo.shipping" :value="true">
                        <span class="checkbox-item__check"></span>
                        <p class="checkbox-item__text">Giao hàng đến địa chỉ</p>
                    </label>
                    <div class="choose-form__content" :class="{ 'show': shipInfo.shipping }">
                        <form class="form-address">
                            <div class="input-item">
                                <label>Tên người nhận *</label>
                                <div class="input-item__inner">
                                    <input type="text" v-model="shipInfo.name" name="name" class="form-control">
                                </div>
                            </div>
                            <div class="input-item">
                                <label>Số điện thoại *</label>
                                <div class="input-item__inner">
                                    <input type="text" v-model="shipInfo.phone" name="phone" class="form-control">
                                </div>
                            </div>
                            <div class="input-item input-address">
                                <label>Địa chỉ người nhận hàng</label>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-address__item">
                                            <label>Tỉnh, Thành phố *</label>
                                            <select v-model="shipInfo.city" class="form-control" :disabled="loadingShipmentFee && loadingShipmentFeeFast">
                                                <option v-for="item in local" :value="item.name">@{{ item.name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-address__item">
                                            <label>Quận, Huyện *</label>
                                            <select v-model="shipInfo.district" class="form-control" :disabled="!shipInfo.city || (loadingShipmentFee && loadingShipmentFeeFast)">
                                                <option v-for="item in (shipInfo.city && local.length > 0) ? local.find(x => x.name == shipInfo.city).districts : []" :value="item.name">@{{ item.name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <div class="input-address__item">
                                            <label>Nhập địa chỉ chi tiết *</label>
                                            <textarea v-model="shipInfo.address" class="form-control" placeholder="VD: Số 65 Quán Nam, Quận, Lê Chân, Tp. Hải Phòng"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- khi select xong địa chỉ sẽ hiện box này kèm theo giá  -->
                            <div class="input-item" v-if="shipmentFee && shipmentFeeFast">
                                <label>Chọp phương thức giao hàng:</label>
                                <div class="checkbox-wrap">
                                    {{-- <label class="checkbox-item">
                                        <input type="radio" name="chohinhthuc" v-model="isFast" :value="true" :disabled="!shipmentFeeFast">
                                        <span class="checkbox-item__check"></span>
                                        <p class="checkbox-item__text">Giao hàng nhanh ( <span>@{{ currency(shipmentFeeFast) }}</span> )
                                            <small>Dự kiến giao hàng: 6-10-2012 đến 10-10-2021</small>
                                        </p>
                                    </label> --}}
                                    <label class="checkbox-item">
                                        <input type="radio" name="chohinhthuc" v-model="isFast" :value="false" :disabled="!shipmentFee">
                                        <span class="checkbox-item__check"></span>
                                        <p class="checkbox-item__text">Giao hàng tiết kiệm ( <span>@{{ currency(shipmentFee)}}</span> )
                                            <small>Dự kiến giao hàng khoảng 5 ngày kể từ ngày thanh toán</small>
                                        </p>
                                    </label>
                                </div>
                            </div>

                            <div class="button-item">
                                {{-- <button type="button" class="btn btn--secondary btn--sm">Cập nhật phí</button> --}}
                                {{-- <button type="submit" class="btn btn--outline btn--sm">Lưu lại địa chỉ</button> --}}
                            </div>
                        </form>
                    </div>
                </li>
                @endif
            </ul>
            <div class="text-center">
                <button type="button" class="btn btn--outline close-popup" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<!-- End/Modal hiển thị thông tin vận chuyển -->
</div>

@endsection

@section('footer')
<script>
    new Vue({
        el: '#cartbox__full',
        data: {
            local: [],
            data: [],
            inputData: [],
            shipInfo: {
                shipping: false,
                name: '{{ auth()->user()->name }}',
                phone: '{{ auth()->user()->phone }}',
                city: 'Hải Phòng',
                district: undefined,
                address: undefined,
            },
            showShipInfo: false,
            loading: false,
            promoCode: undefined,
            promoData: undefined,
            priceBook: 0,
            amountBook: 0,
            isFast: false,
            shipmentFee: undefined,
            shipmentFeeFast: undefined,
            supportShipping: true,
            supportShippingFast: true,
            loadingShipmentFee: false,
            loadingShipmentFeeFast: false,
            shipCOD: false,
            pendingTimer: undefined
        },
        mounted() {
            this.getData();
            this.getLocalData();

            const oldShipInfo = JSON.parse(`{{ json_encode(old("ship_info")) }}`);
            if (oldShipInfo) {
                this.shipInfo = oldShipInfo;
            }
        },
        methods: {
            getShipmentFee() {
                const weightItem = 500;
                if (this.shipInfo.district && '{{ config('settings.province_shipment') }}' && '{{ config('settings.district_shipment') }}') {
                    this.loadingShipmentFee = true;
                    this.loadingShipmentFeeFast = true;
                    axios.get("{{ route('api.shipping.getShipmentFee') }}", {
                        params: {
                            pick_province: '{{ config('settings.province_shipment') }}',
                            pick_district: '{{ config('settings.district_shipment') }}',
                            province: this.shipInfo.city,
                            district: this.shipInfo.district,
                            weight: this.amountBook * weightItem,
                            price: this.priceBook,
                            transport: "road",
                            xfast: 0
                        }
                    }).then(res => {
                        this.shipmentFee = res.fee.fee;
                        this.supportShipping = res.fee.delivery;
                    }).finally(() => {
                        this.loadingShipmentFee = false;
                    });
                    axios.get("{{ route('api.shipping.getShipmentFee') }}", {
                        params: {
                            pick_province: '{{ config('settings.province_shipment') }}',
                            pick_district: '{{ config('settings.district_shipment') }}',
                            province: this.shipInfo.city,
                            district: this.shipInfo.district,
                            weight: this.amountBook * weightItem,
                            price: this.priceBook,
                            transport: "road",
                            xfast: 1
                        }
                    }).then(res => {
                        this.shipmentFeeFast = res.fee.fee;
                        this.supportShippingFast = res.fee.delivery;
                    }).finally(() => {
                        this.loadingShipmentFeeFast = false;
                    });
                }
            },
            changeAmountBook(event) {
                if (event.target.value) {
                    this.priceBook = 0;
                    this.amountBook = 0;
                    this.inputData.filter(x => x.type == '{{ \App\Constants\ObjectType::BOOK }}').forEach(element => {
                        if (element.amount) {
                            this.priceBook += element.price * parseInt(element.amount);
                            this.amountBook += parseInt(element.amount);
                        }
                    });
                    this.loadingShipmentFee = true;
                    clearTimeout(this.pendingTimer);
                    this.pendingTimer = setTimeout(() => {
                        this.getShipmentFee();
                        this.loadingShipmentFee = false;
                    }, 1000);
                }
            },
            getLocalData() {
                axios.get('{{ url("json/vietnam-db.json") }}').then(res => {
                    res.sort((a, b) => a.name.localeCompare(b.name));
                    this.local = res;
                });
            },
            getData() {
                this.loading = true;
                axios.get("{{ route('cart.get_data') }}").then(res => {
                    this.data = res;
                    this.inputData = _.cloneDeep(this.data);
                    this.priceBook = 0;
                    this.amountBook = 0;
                    const applyPromo = this.validatePromo();
                    if (!applyPromo) {
                        this.promoData = undefined;
                        this.promoCode = undefined;
                    }

                    this.inputData = this.inputData.map(item => {
                        if (item.type != '{{ \App\Constants\ObjectType::COURSE }}')
                        {
                            if (item.type == '{{ \App\Constants\ObjectType::BOOK }}') {
                                console.log(item.price)
                                this.priceBook += item.price * parseInt(item.amount);
                                this.amountBook += parseInt(item.amount);
                            };
                            return item;
                        }
                        if (item.price == 0) return item;
                        if (!applyPromo) return item;

                        switch (this.promoData.type) {
                            case '{{ \App\Constants\PromoType::DISCOUNT }}':
                                item.del_price = item.price;
                                item.price = Math.ceil(item.price - item.price * this.promoData.value / 100);
                                break;

                            case '{{ \App\Constants\PromoType::SAME_PRICE }}':
                                if (this.promoData.value >= item.price) return item;
                                item.del_price = item.price;
                                item.price = this.promoData.value;
                                break;
                        }
                        return item;
                    });

                    this.updateShowShipInfo();
                }).then(() => {
                    this.loading = false;

                    $('html, body').animate({
                        scrollTop: $('.cart-detail').offset().top - $(window).height() / 5
                    }, 500);
                }).finally(() => {
                    this.getShipmentFee();
                });
            },
            removeCartItem(id) {
                this.loading = true;
                axios.post("{{ route('cart.delete') }}", {
                    id
                }).then(() => {
                    this.getData();
                });
            },
            currency(x) {
                if (typeof x === 'undefined') {
                    return 'Chưa xác định';
                }
                return currency(x);
            },
            submitCart() {
                if (this.showShipInfo) {
                    if (!this.shipInfo.name || !this.shipInfo.phone) {
                        bootbox.alert('Chưa có thông tin người nhận. Vui lòng kiểm tra lại.');
                        return;
                    }

                    if (this.shipInfo.shipping) {
                        if (!this.shipInfo.city || !this.shipInfo.district || !this.shipInfo.address) {
                            bootbox.alert('Địa chỉ giao hàng chưa đầy đủ. Vui lòng kiểm tra lại.');
                            return;
                        }
                    }
                }

                bootbox.confirm({
                    message: 'Bạn chắc chắn muốn thanh toán đơn hàng?',
                    buttons: {
                        confirm: {
                            label: 'Xác nhận',
                            className: 'btn--sm btn--success'
                        },
                        cancel: {
                            label: 'Hủy bỏ',
                            className: 'btn--sm bg-dark'
                        }
                    },
                    callback: r => {
                        if (!r) return;
                        let shipmentFee = this.shipmentFee;
                        if (this.isFast) {
                            shipmentFee = this.shipmentFeeFast;
                        }
                        const shipment = {
                            ship_money: shipmentFee,
                            is_fast: this.isFast,
                            is_cod: this.shipCOD
                        }
                        this.loading = true;
                        axios.post('{{ route("cart.confirm") }}', {
                            cart: this.inputData,
                            ship_info: this.shipInfo,
                            promo_code: this.promoCode,
                            shipment: shipment
                        }).then(() => {
                            location.href = '{{ route("cart.complete") }}';
                        }).catch(err => {
                            const msg = Object.values(err.errors).map(x => x.join("\n")).join("\n");
                            bootbox.alert(msg, function(){
                                window.location.href = "{{ route('user.recharge') }}"
                            });
                            this.loading = false;
                        });
                    }
                });
            },
            updateShowShipInfo() {
                this.showShipInfo = this.inputData.filter(x => x.type != '{{ \App\Constants\ObjectType::COURSE }}').length > 0;
            },
            getPromo() {
                axios.post("{{ route('cart.get_promo') }}", {
                    code: this.promoCode
                }).then(res => {
                    this.promoData = res;

                    if (!this.validatePromo()) {
                        bootbox.alert('Giỏ hàng không đủ điều kiện để áp dụng mã khuyến mại.');
                        this.promoData = undefined;
                        this.promoCode = undefined;
                    }

                    this.getData();
                }).catch(err => {
                    this.promoCode = undefined;

                    const msg = Object.values(err.errors).map(x => x.join("\n")).join("\n");
                    bootbox.alert(msg);
                });
            },
            clearPromo() {
                this.promoData = undefined;
                this.promoCode = undefined;
                this.getData();
            },
            totalPrice() {
                let shipmentFee = 0;
                if (this.shipInfo.shipping) {
                    if (this.isFast) {
                        shipmentFee = this.shipmentFeeFast;
                    } else {
                        shipmentFee = this.shipmentFee
                    }
                }
                return shipmentFee + this.inputData.reduce((total, item) => {
                    return total + item.amount * item.price;
                }, 0);
            },
            validatePromo() {
                const data = this.inputData;
                if (this.promoData === undefined) return false;

                if (this.promoData.combo_courses.length == 0) return true;

                const courseIds = data.filter(x => x.type == '{{ \App\Constants\ObjectType::COURSE }}').map(x => parseInt(x.object_id));
                const courseIdsNotInCombo = this.promoData.combo_courses.filter(id => !courseIds.includes(parseInt(id)));
                if (courseIdsNotInCombo.length == 0) return true;

                return false;
            }
        },
        computed: {
            getDistrict() {
                return this.shipInfo.district;
            }
        },
        watch: {
            getDistrict() {
                this.getShipmentFee();
            }
        }
    });

</script>
@endsection
