@extends('backend.master')

@section('title')
Lên lịch học Zoom
@endsection

@section('content-header')
<style>
    .card {
        width: 700px;
        margin: auto;
    }
    #form__create__zoom {
        width: 600px;
    }
    #form__create__zoom tr>td {
        padding-bottom: 1rem;
        padding-right: 1rem;
    }
</style>
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Lên lịch học Zoom</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.zoom.meetings', ['id' => $id]) }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
        </div>
    </div><!-- /.col -->
</div>
@endsection

@section('content')
<div id="js-create-zoom">
<form action="{{ route('admin.zoom.store') }}" method="POST">
    <input type="hidden" name="id" value="{{ $id }}">
    @csrf
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <table id="form__create__zoom">
                        <tr>
                            <td>Chủ đề</td>
                            <td><input type="text" class="form-control form-control-sm" name="topic" placeholder="Topic" required></td>
                        </tr>
                        <tr>
                            <td>Mô tả</td>
                            <td><textarea class="form-control form-control-sm" name="agenda" placeholder="(không bắt buộc)"></textarea></td>
                        </tr>
                        <tr>
                            <td>Khi nào</td>
                            <td><input type="datetime-local" class="form-control form-control-sm" name="start_time" v-model="startTime" required></td>
                        </tr>
                        <tr>
                            <td>Thời lượng</td>
                            <td>
                                <div class="input-group input-group-sm mb-3">
                                    <input type="number" class="form-control" name="duration" placeholder="Phút" value="60" required>
                                    <div class="input-group-append">
                                      <span class="input-group-text">phút</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                      <input type="checkbox" class="custom-control-input" id="recurrences" v-model="isRecurrence" name="recurrence">
                                      <label class="custom-control-label" for="recurrences" style="font-weight: 400">Lớp học định kỳ</label>
                                    </div>
                                </div>
                                <div id="info-recurrence" class="">
                                    <div class="row">
                                        <div class="col-sm-7">
                                            <div class="form-group">
                                                <label>Tái diễn</label>
                                                <select class="custom-select" name="recurrence_type" v-model="recurrenceType">
                                                  <option value="1">Hàng ngày</option>
                                                  <option value="2">Hàng tuần</option>
                                                  <option value="3">Hàng tháng</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label>Lặp lại mỗi</label>
                                                <div class="input-group mb-3">
                                                    <input type="number" min="1" class="form-control" value="1" name="recurrence_repeat_interval">
                                                    <div class="input-group-append">
                                                      <span class="input-group-text">@{{recurrenceTypeText}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-5" v-if="recurrenceType != 1">
                                            <div class="form-group">
                                                <label>Diễn ra vào lúc </label>
                                                <select v-if="recurrenceType == 2" multiple class="form-control" name="recurrence_weekly_days[]" v-model="recurrenceWeeklyDays">
                                                  <option value="1">Chủ nhật</option>
                                                  <option value="2">Thứ 2</option>
                                                  <option value="3">Thứ 3</option>
                                                  <option value="4">Thứ 4</option>
                                                  <option value="5">Thứ 5</option>
                                                  <option value="6">Thứ 6</option>
                                                  <option value="7">Thứ 7</option>
                                                </select>
                                                <span v-if="recurrenceType == 2">(Giữ Ctrl để chọn nhiều)</span>
                                                <div style="display: flex" v-if="recurrenceType == 3">
                                                    <span>Ngày</span>
                                                    <select class="form-control form-control-sm" name="recurrence_monthly_day" v-model="recurrenceMonthlyDay" style="width: 60px; margin-left: 10px; margin-right: 10px;">
                                                        <option v-for="item in 31" :value="item">@{{item}}</option>
                                                    </select>
                                                    <span>trong tháng</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="form-group">
                                                <label>Ngày kết thúc</label>
                                                <div class="form-check" style="margin-bottom: 10px;">
                                                  <input class="form-check-input" type="radio" value="datetime" id="recurrence-end-type-datetime" name="recurrence_end_type" checked="">
                                                  <label class="form-check-label" for="recurrence-end-type-datetime" style="display: flex">Tới lúc <input type="date" class="form-control form-control-sm" name="recurrence_end_date_time" v-model="recurrenceEndDateTime" style="width: 150px; margin-left: 10px;"></label>
                                                </div>
                                                <div class="form-check">
                                                  <input class="form-check-input" type="radio" value="times" id="recurrence-end-type-times" name="recurrence_end_type">
                                                  <label class="form-check-label" for="recurrence-end-type-times" style="display: flex">Sau <input type="number" class="form-control form-control-sm" name="recurrence_end_times" min="1" value="7" style="width: 60px; margin-left: 10px; margin-right: 10px;"> buổi học</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Bảo mật</td>
                            <td><input type="password" class="form-control form-control-sm" name="password" placeholder="Mật khẩu" autocomplete="off" required></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><button class="btn btn-primary">Lưu</button> <a href="{{ route('admin.zoom.index') }}" class="btn btn-outline-secondary">Huỷ</a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
</div>
@endsection

@section('script')
    <script>
        new Vue({
        el: '#js-create-zoom',
        data: {
            date: new Date(),
            isRecurrence: false,
            recurrenceType: 1,
            recurrenceTypeText: 'ngày',
            startTime: moment(new Date()).format('YYYY-MM-DDThh:mm'),
            recurrenceEndDateTime: undefined,
            recurrenceWeeklyDays: [moment(new Date()).day()+1],
            recurrenceMonthlyDay: moment(new Date()).format('DD'),
        },
        mounted() {
            $( "#info-recurrence" ).hide();
            this.recurrenceEndDateTime = moment(new Date(this.date.setMonth(this.date.getMonth()+6))).format('YYYY-MM-DD');
        },
        methods: {
        },
        watch: {
            isRecurrence() {
                $( "#info-recurrence" ).slideToggle();
            },
            recurrenceType() {
                if (this.recurrenceType == 1) {
                    this.recurrenceTypeText = 'ngày';
                } else if (this.recurrenceType == 2) {
                    this.recurrenceTypeText = 'tuần';
                } else if (this.recurrenceType == 3) {
                    this.recurrenceTypeText = 'tháng';
                }
            },
        }
    });
    </script>
@endsection
