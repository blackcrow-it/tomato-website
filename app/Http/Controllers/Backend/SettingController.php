<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SettingUploadImageRequest;
use App\Setting;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;
use Storage;

class SettingController extends Controller
{
    public function edit($view)
    {
        return view('backend.setting.' . $view);
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

    public function uploadImage(SettingUploadImageRequest $request)
    {
        $file = $request->file('image');
        $path = $file->storePubliclyAs('settings', $request->input('key') . '.png', 's3');

        $imageUrl = Storage::disk('s3')->url($path);

        return [
            'src' => $imageUrl . '?t=' . time()
        ];
    }
}
