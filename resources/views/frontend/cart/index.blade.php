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
                <p><b>Khoá học online</b> trực tiếp trên website tomatoonline.edu.vn, không có hình thức vận chuyển.</p>
                <p>Đối với sản phẩm là <b>Tài liệu</b> sẽ có phí vận chuyển tuỳ theo khu vực địa chị nhận hàng của học viên.</p>
            </div>
            <hr>
            <div class="h3">Thông tin người nhận</div>
            <div class="">
                <div class="input-item">
                    <label>Tên người nhận *</label>
                    <div class="input-item__inner">
                        <input type="text" v-model="shipInfo.name" class="form-control">
                    </div>
                </div>
                <div class="input-item">
                    <label>Số điện thoại *</label>
                    <div class="input-item__inner">
                        <input type="text" v-model="shipInfo.phone" class="form-control">
                    </div>
                </div>
            </div>
            <hr>
            <div class="h3">Chọn hình thức giao hàng</div>
            <div id="form-address-modal">
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
                    <li class="choose-form__item">
                        <label class="checkbox-item">
                            <input type="radio" v-model="shipInfo.shipping" :value="true">
                            <span class="checkbox-item__check"></span>
                            <p class="checkbox-item__text">Giao hàng đến địa chỉ (<b class="f-price">Tính phí vận chuyển</b>)</p>
                        </label>
                        <div class="choose-form__content" :class="{ 'show': shipInfo.shipping }">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-address__item">
                                        <label>Tỉnh, Thành phố</label>
                                        <select v-model="shipInfo.city" class="form-control">
                                            <option v-for="item in local" :value="item.name">@{{ item.name }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-address__item">
                                        <label>Quận, Huyện</label>
                                        <select v-model="shipInfo.district" class="form-control" :disabled="!shipInfo.city">
                                            <option v-for="item in (shipInfo.city && local.length > 0) ? local.find(x => x.name == shipInfo.city).districts : []" :value="item.name">@{{ item.name }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="input-address__item">
                                        <label>Nhập địa chỉ</label>
                                        <textarea v-model="shipInfo.address" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
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
                        <tr v-if="inputData.length == 0" class="bg-light">
                            <td colspan="99" class="text-left">
                                Bạn chưa có sản phẩm nào trong giỏ hàng
                            </td>
                        </tr>
                        <tr v-if="inputData.filter(x => x.type == '{{ \App\Constants\ObjectType::COURSE }}').length > 0" class="bg-light">
                            <td colspan="99" class="text-left">
                                <b>Khóa học online</b><br>
                                <small class="text-danger">* Khoá học online học trực tiếp không hỗ trợ vận chuyển</small>
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
                        <tr class="bg-light">
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

                    this.updateShowShipInfo();
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
                if (this.showShipInfo) {
                    if (!this.shipInfo.name || !this.shipInfo.phone) {
                        alert('Chưa có thông tin người nhận. Vui lòng kiểm tra lại.');
                        return;
                    }

                    if (this.shipInfo.shipping) {
                        if (!this.shipInfo.city || !this.shipInfo.district || !this.shipInfo.address) {
                            alert('Địa chỉ giao hàng chưa đầy đủ. Vui lòng kiểm tra lại.');
                            return;
                        }
                    }
                }

                const confirmResult = confirm('Bạn chắc chắn muốn thanh toán đơn hàng?');
                if (!confirmResult) return;

                this.loading = true;
                axios.post('{{ route("cart.confirm") }}', {
                    cart: this.inputData,
                    ship_info: this.shipInfo
                }).then(() => {
                    location.href = '{{ route("cart.complete") }}';
                }).catch(err => {
                    const msg = Object.values(err.errors).map(x => x.join("\n")).join("\n");
                    alert(msg);
                    this.loading = false;
                });
            },
            updateShowShipInfo() {
                this.showShipInfo = this.inputData.filter(x => x.type != '{{ \App\Constants\ObjectType::COURSE }}').length > 0;
            },
        },
    });

</script>
@endsection
