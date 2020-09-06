@extends('frontend.user.master')

@section('header')
<title>Thông tin cá nhân</title>
@endsection

@section('content')
<h2 class="user-page__title">Thông tin cá nhân</h2>

<div class="user-page__infoPersonal" id="app">
    <table>
        <tbody>
            <tr>
                <td class="td-label">
                    <p>Họ và Tên</p>
                </td>
                <td class="td-content">
                    <div class="collapse" id="collapse-name" :class="{ show: errors.name }">
                        <div class="input-item">
                            <div class="input-item__inner">
                                <input type="text" v-model="user.name" placeholder="Họ và tên" class="form-control" :class="{ 'is-invalid': errors.name }">
                            </div>
                            <p v-if="errors.name" class="error">@{{ errors.name[0] }}</p>
                        </div>
                        <div class="button-item">
                            <button type="button" class="btn btn--sm btn--secondary" @click="submitData">Lưu lại</button>
                        </div>
                    </div>

                    <p class="collapse-text f-name">@{{ user.name || 'Chưa có dữ liệu' }}</p>
                </td>
                <td class="td-action">
                    <a class="btn-edit" data-toggle="collapse" href="#collapse-name" role="button" aria-expanded="false" aria-controls="collapse-name">
                        <span class="t-edit"><i class="fa fa-pencil"></i>chỉnh sửa</span>
                        <span class="t-close"><i class="fa fa-close"></i>đóng</span>
                    </a>
                </td>
            </tr>
            <tr>
                <td class="td-label">
                    <p>Email</p>
                </td>
                <td class="td-content">
                    <p v-if="user.email" class="collapse-text f-email">@{{ user.email }}</p>
                    <a v-else href="{{ route('auth.google') }}">Liên kết với tài khoản Google</a>
                </td>
                <td class="td-action">
                    <span v-if="user.email" class="btn-lock" data-toggle="tooltip" data-placement="top" title="Trường không thể sửa">
                        <i class="fa fa-lock"></i>
                    </span>
                </td>
            </tr>
            <tr>
                <td class="td-label">
                    <p>Ngày sinh</p>
                </td>
                <td class="td-content">
                    <div class="collapse" id="collapse-birth" :class="{ show: errors.birthday }">
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="input-item">
                                    <div class="input-item__inner">
                                        <select v-model="user.birthday_day" class="form-control" :class="{ 'is-invalid': errors.birthday }">
                                            <option v-for="i in maxDaysInMonth" :value="i">Ngày @{{ i }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="input-item">
                                    <div class="input-item__inner">
                                        <select v-model="user.birthday_month" class="form-control" :class="{ 'is-invalid': errors.birthday }" @change="changeBirthdayInput">
                                            <option v-for="i in 12" :value="i">Tháng @{{ i }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="input-item">
                                    <div class="input-item__inner">
                                        <select v-model="user.birthday_year" class="form-control" :class="{ 'is-invalid': errors.birthday }" @change="changeBirthdayInput">
                                            <option v-for="i in 50" :value="currentYear - i">Năm @{{ currentYear - i }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p v-if="errors.name" class="error">@{{ errors.birthday[0] }}</p>
                        <div class="button-item">
                            <button type="button" class="btn btn--sm btn--secondary" @click="submitData">Lưu lại</button>
                        </div>
                    </div>
                    <p class="collapse-text f-text null">@{{ user.birthday_display || 'Chưa có dữ liệu' }}</p>
                </td>
                <td class="td-action">
                    <a class="btn-edit" data-toggle="collapse" href="#collapse-birth" role="button" aria-expanded="false" aria-controls="collapse-birth">
                        <span class="t-edit"><i class="fa fa-pencil"></i>chỉnh sửa</span>
                        <span class="t-close"><i class="fa fa-close"></i>đóng</span>
                    </a>
                </td>
            </tr>
            <tr>
                <td class="td-label">
                    <p>Số điện thoại</p>
                </td>
                <td class="td-content">
                    <div class="collapse" id="collapse-phone" :class="{ show: errors.phone }">
                        <div class="input-item">
                            <div class="input-item__inner">
                                <input type="text" v-model="user.phone" class="form-control" placeholder="Ví dụ: 0123456789" :class="{ 'is-invalid': errors.phone }">
                            </div>
                            <p v-if="errors.phone" class="error">@{{ errors.phone[0] }}</p>
                        </div>
                        <div class="button-item">
                            <button type="button" class="btn btn--sm btn--secondary" @click="submitData">Lưu lại</button>
                        </div>
                    </div>

                    <p class="collapse-text f-text null">@{{ user.phone || 'Chưa có dữ liệu' }}</p>
                </td>
                <td class="td-action">
                    <a class="btn-edit" data-toggle="collapse" href="#collapse-phone" role="button" aria-expanded="false" aria-controls="collapse-phone">
                        <span class="t-edit"><i class="fa fa-pencil"></i>chỉnh sửa</span>
                        <span class="t-close"><i class="fa fa-close"></i>đóng</span>
                    </a>
                </td>
            </tr>
            <tr>
                <td class="td-label">
                    <p>Địa chỉ</p>
                </td>
                <td class="td-content">
                    <div class="collapse" id="collapse-address" :class="{ show: errors.address }">
                        <div class="input-item">
                            <div class="input-item__inner">
                                <input type="text" v-model="user.address" class="form-control" placeholder="Ví dụ: xã, phường-huyện-thành phố" :class="{ 'is-invalid': errors.birthday }">
                            </div>
                            <p v-if="errors.name" class="error">@{{ errors.name[0] }}</p>
                        </div>
                        <div class="button-item">
                            <button type="submit" class="btn btn--sm btn--secondary" @click="submitData">Lưu lại</button>
                        </div>
                    </div>

                    <p class="collapse-text f-text null">@{{ user.address || 'Chưa có dữ liệu' }}</p>
                </td>
                <td class="td-action">
                    <a class="btn-edit" data-toggle="collapse" href="#collapse-address" role="button" aria-expanded="false" aria-controls="collapse-address">
                        <span class="t-edit"><i class="fa fa-pencil"></i>chỉnh sửa</span>
                        <span class="t-close"><i class="fa fa-close"></i>đóng</span>
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

@section('footer')
<script>
    new Vue({
        el: '#app',
        data: {
            user: {},
            errors: {},
            currentYear: moment().format('YYYY'),
            maxDaysInMonth: 31,
        },
        mounted() {
            this.getData();
        },
        methods: {
            getData() {
                axios.get("{{ route('user.info.get_data') }}").then(res => {
                    this.user = res;

                    if (!this.user.birthday) return;

                    const birthday = moment(this.user.birthday);
                    this.user.birthday_day = birthday.format('D');
                    this.user.birthday_month = birthday.format('M');
                    this.user.birthday_year = birthday.format('YYYY');

                    this.user.birthday_display = birthday.format('DD-MM-YYYY');

                    this.changeBirthdayInput();
                });
            },
            submitData() {
                if (this.user.birthday_year && this.user.birthday_month && this.user.birthday_day) {
                    this.user.birthday = moment(this.user.birthday_year + '-' + this.user.birthday_month + '-' + this.user.birthday_day, 'YYYY-M-D').format('YYYY-MM-DD');
                }

                axios.post("{{ route('user.info.submit_data') }}", this.user).then(() => {
                    this.getData();
                    $('.collapse').collapse('hide');
                    this.errors = [];
                }).catch(res => {
                    this.errors = res.errors;
                });
            },
            changeBirthdayInput() {
                const selectedMonth = parseInt(this.user.birthday_month);
                const selectedYear = parseInt(this.user.birthday_year);

                if ([1, 3, 5, 7, 8, 10, 12].includes(selectedMonth)) {
                    this.maxDaysInMonth = 31;
                    return;
                }

                if ([4, 6, 9, 11].includes(selectedMonth)) {
                    this.maxDaysInMonth = 30;
                    return;
                }

                if ((selectedYear % 4 == 0 && selectedYear % 100 != 0) || selectedYear % 400 == 0) {
                    this.maxDaysInMonth = 29;
                    return;
                }

                this.maxDaysInMonth = 28;
            },
        },
    });

</script>
@endsection
