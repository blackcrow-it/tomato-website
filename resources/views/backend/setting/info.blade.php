@extends('backend.setting.master')

@section('setting_title')
Cài đặt thông tin chung
@endsection

@section('setting_content')
<div class="form-group">
    <label>Tên công ty, trung tâm...</label>
    <input type="text" name="company" value="{{ config('settings.company') }}" class="form-control" placeholder="Tên công ty, trung tâm...">
</div>
<div class="form-group">
    <label>Điện thoại 1</label>
    <input type="text" name="hotline1" value="{{ config('settings.hotline1') }}" class="form-control" placeholder="Điện thoại 1">
</div>
<div class="form-group">
    <label>Điện thoại 2</label>
    <input type="text" name="hotline2" value="{{ config('settings.hotline2') }}" class="form-control" placeholder="Điện thoại 2">
</div>
<div class="form-group">
    <label>Điện thoại 3</label>
    <input type="text" name="hotline3" value="{{ config('settings.hotline3') }}" class="form-control" placeholder="Điện thoại 3">
</div>
<div class="form-group">
    <label>Điện thoại 4</label>
    <input type="text" name="hotline4" value="{{ config('settings.hotline4') }}" class="form-control" placeholder="Điện thoại 4">
</div>
<div class="form-group">
    <label>Địa chỉ cơ sở 1</label>
    <input type="text" name="address1" value="{{ config('settings.address1') }}" class="form-control" placeholder="Địa chỉ cơ sở 1">
</div>
<div class="form-group">
    <label>Địa chỉ cơ sở 2</label>
    <input type="text" name="address2" value="{{ config('settings.address2') }}" class="form-control" placeholder="Địa chỉ cơ sở 2">
</div>
<div class="form-group">
    <label>Địa chỉ cơ sở 3</label>
    <input type="text" name="address3" value="{{ config('settings.address3') }}" class="form-control" placeholder="Địa chỉ cơ sở 3">
</div>
<div class="form-group">
    <label>Địa chỉ cơ sở 4</label>
    <input type="text" name="address4" value="{{ config('settings.address4') }}" class="form-control" placeholder="Địa chỉ cơ sở 4">
</div>
<div class="form-group">
    <label>Địa chỉ cơ sở 5</label>
    <input type="text" name="address5" value="{{ config('settings.address5') }}" class="form-control" placeholder="Địa chỉ cơ sở 5">
</div>
<div class="form-group">
    <label>Địa chỉ cơ sở 6</label>
    <input type="text" name="address6" value="{{ config('settings.address6') }}" class="form-control" placeholder="Địa chỉ cơ sở 6">
</div>
<div class="form-group form-row">
    <div class="col">
        <label>Tỉnh, Thành phố (địa chỉ lấy sách)</label>
        <select v-model="province" name="province_shipment" class="form-control">
            <option v-for="item in local" :value="item.name">@{{ item.name }}</option>
        </select>
    </div>
    <div class="col">
        <label>Quận, Huyện</label>
        <select v-model="district" name="district_shipment" class="form-control" :disabled="!province">
            <option v-for="item in (province && local.length > 0) ? local.find(x => x.name == province).districts : []" :value="item.name">@{{ item.name }}</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label>Email</label>
    <input type="text" name="email" value="{{ config('settings.email') }}" class="form-control" placeholder="Email">
</div>
<div class="form-group">
    <label>Facebook</label>
    <input type="text" name="facebook" value="{{ config('settings.facebook') }}" class="form-control" placeholder="Facebook">
</div>
<div class="form-group">
    <label>Youtube</label>
    <input type="text" name="youtube" value="{{ config('settings.youtube') }}" class="form-control" placeholder="Youtube">
</div>
<div class="form-group">
    <label>Tiktok</label>
    <input type="text" name="tiktok" value="{{ config('settings.tiktok') }}" class="form-control" placeholder="Tiktok">
</div>
<div class="form-group">
    <label>Chứng nhận Bộ công thương</label>
    <textarea name="certification" class="editor">{!! config('settings.certification') !!}</textarea>
</div>
@endsection
