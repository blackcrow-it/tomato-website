@extends('backend.master')

@section('title')
Nạp tiền
@endsection

@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Nạp tiền</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">

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

<div id="recharge">
    <div class="table-responsive">
        <table class="table table-hover table-light text-nowrap">
            <thead class="bg-lightblue">
                <tr>
                    <th>Loại</th>
                    <th>Mã giao dịch</th>
                    <th>Thời gian</th>
                    <th>Số tiền</th>
                    <th>Thành viên</th>
                    <th>Trạng thái</th>
                    <th>Thông tin</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in data.data">
                    <td>
                        <span v-if="item.type == '{{ \App\Constants\RechargePartner::MOMO }}'" class="badge bg-red">Momo</span>
                        <span v-if="item.type == '{{ \App\Constants\RechargePartner::EPAY }}'" class="badge bg-orange">Epay</span>
                        <span v-if="item.type == '{{ \App\Constants\RechargePartner::DIRECT }}'" class="badge bg-info">Trực tiếp</span>
                    </td>
                    <td>@{{ item.trans_id }}</td>
                    <td>@{{ datetimeFormat(item.created_at) }}</td>
                    <td>@{{ currency(item.amount) }}</td>
                    <td>
                        @{{ item.user.username }}<br>
                        @{{ item.user.email }}
                    </td>
                    <td>
                        <span v-if="item.status == '{{ \App\Constants\RechargeStatus::PENDING }}'" class="badge badge-warning">Đang chờ</span>
                        <span v-if="item.status == '{{ \App\Constants\RechargeStatus::SUCCESS }}'" class="badge badge-success">Thành công</span>
                        <span v-if="item.status == '{{ \App\Constants\RechargeStatus::CANCEL }}'" class="badge badge-danger">Thất bại</span>
                    </td>
                    <td>
                        <div>
                            <a data-toggle="collapse" :href="'#request-data-' + index">Request data</a>
                            <pre class="collapse" :id="'request-data-' + index">@{{ JSON.stringify(JSON.parse(item.request_data), undefined, 4)  }}</pre>
                        </div>
                        <div>
                            <a data-toggle="collapse" :href="'#callback-data-' + index">Callback data</a>
                            <pre class="collapse" :id="'callback-data-' + index">@{{ JSON.stringify(JSON.parse(item.callback_data), undefined, 4) }}</pre>
                        </div>
                        <div>
                            <a data-toggle="collapse" :href="'#notify-data-' + index">Notify data</a>
                            <pre class="collapse" :id="'notify-data-' + index">@{{ JSON.stringify(JSON.parse(item.notify_data), undefined, 4) }}</pre>
                        </div>
                    </td>
                    <td>
                        <button v-if="canShowRecheckButton(item)" type="button" class="btn btn-sm btn-info" title="Kiểm tra lại kết quả giao dịch" @click="recheck(index)" :disabled="item.__loading">
                            <span v-if="item.__loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <i v-else class="fas fa-sync-alt"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <paginate v-model="data.current_page" :click-handler="getData" :page-count="data.last_page" :prev-text="'Trước'" :next-text="'Sau'" :container-class="'pagination'" :page-class="'page-item'" :page-link-class="'page-link'" :prev-class="'page-item'" :prev-link-class="'page-link'" :next-class="'page-item'" :next-link-class="'page-link'"></paginate>
</div>
@endsection

@section('script')
<script>
    new Vue({
        el: '#recharge',
        data: {
            data: {
                data: [],
                current_page: 1,
                last_page: 0,
            },
        },
        mounted() {
            this.getData();
        },
        methods: {
            getData() {
                axios.get('{{ route("admin.recharge.get_data") }}', {
                    params: {
                        page: this.data.current_page
                    }
                }).then(res => {
                    this.data = res;
                });
            },
            currency(x) {
                return currency(x);
            },
            datetimeFormat(str) {
                return moment(str).format('YYYY-MM-DD HH:mm:ss');
            },
            recheck(index) {
                this.$set(this.data.data[index], '__loading', true);
                axios.post('{{ route("admin.recharge.recheck") }}', {
                    id: this.data.data[index].id
                }).then(res => {
                    this.$set(this.data.data, index, res);
                }).then(() => {
                    this.$set(this.data.data[index], '__loading', false);
                });
            },
            canShowRecheckButton(item) {
                if (item.status != '{{ \App\Constants\RechargeStatus::PENDING }}') return true;
                return moment().diff(moment(item.updated_at), 'hours') >= 2;
            },
        },
    });

</script>
@endsection
