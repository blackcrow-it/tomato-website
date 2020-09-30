<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Setting;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;

class SettingController extends Controller
{
    public function editHomepage()
    {
        return view('backend.setting.homepage');
    }

    public function submit(Request $request)
    {
        try {
            DB::beginTransaction();

            foreach ($request->except('_token') as $key => $value) {
                Setting::updateOrInsert([
                    'key' => $key
                ], [
                    'value' => $value
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Thay đổi cài đặt thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);
            return redirect()->back()->withErrors('Có lỗi xảy ra. Vui lòng thử lại.');
        }
    }
}
