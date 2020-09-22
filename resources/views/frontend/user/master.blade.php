@extends('frontend.master')

@section('body')
<section class="section sec-user-page">
    <div class="container">
        <div class="user-page">
            <div class="row">
                <div class="col-lg-3">
                    <div class="user-page__sidebar">
                        <div class="user-page__avatar">
                            <div class="f-avatar" id="user-avatar" :style="{ 'opacity': isLoading ? 0.5 : 1, 'pointer-events': isLoading ? 'none' : 'auto' }">
                                <img :src="avatar">
                                <label>
                                    <input type="file" accept="image/*" @change="inputFileChange">
                                    <span> <i class="fa fa-camera"></i>Cập nhật</span>
                                </label>
                            </div>

                            <div class="f-price">
                                <p>Số dư: <b>{{ currency(auth()->user()->money, '0 ₫') }}</b></p>
                            </div>
                        </div>
                        <ul class="user-page__page">
                            <li class="{{ request()->routeIs('user.info') ? 'current' : '' }}"><a href="{{ route('user.info') }}"><i class="fa fa-vcard-o"></i>Trang cá nhân</a></li>
                            <li class=""><a href=""><i class="fa fa-bell-o"></i>Thông báo <small>(3)</small></a></li>
                            <li class="{{ request()->routeIs('user.invoice') ? 'current' : '' }}"><a href="{{ route('user.invoice') }}"><i class="fa fa-cart-arrow-down"></i>Lịch sử mua hàng</a></li>
                            <li class="{{ request()->routeIs('user.my_course') ? 'current' : '' }}"><a href="{{ route('user.my_course') }}"><i class="fa fa-server"></i>Khoá học của tôi</a></li>
                            <li class="{{ request()->routeIs('user.recharge') ? 'current' : '' }}"><a href="{{ route('user.recharge') }}"><i class="fa fa-credit-card"></i>Nạp tiền</a></li>
                            <li class=""><a href=""><i class="fa fa-edit"></i>Thay đổi mật khẩu</a></li>
                            <li><a href="#" onclick="$('#js-logout-form').submit();"><i class="fa fa-sign-out"></i>Đăng xuất</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="user-page__content">
                        @yield('content')
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
        el: '#user-avatar',
        data: {
            avatar: '{{ auth()->user()->avatar ?? asset("tomato/assets/img/image/default-avatar.jpg") }}',
            isLoading: false
        },
        methods: {
            inputFileChange(e) {
                const files = e.target.files;
                if (files.length == 0) return;

                const file = files[0];
                const formData = new FormData;
                formData.append('avatar', file);
                this.isLoading = true;
                axios.post('{{ route("user.upload_avatar") }}', formData)
                    .then(res => {
                        this.avatar = res;
                    })
                    .then(() => {
                        this.isLoading = false;
                    });
            },
        },
    });

</script>

@yield('user_script')
@endsection
