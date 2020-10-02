<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SettingUploadImageRequest;
use App\Setting;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;
use Socialite;
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

    public function redirectAuthGoogleDriveApi()
    {
        return Socialite::driver('google')
            ->scopes([
                'https://www.googleapis.com/auth/drive.metadata.readonly'
            ])
            ->with([
                'access_type' => 'offline',
            ])
            ->redirectUrl(route('admin.setting.drive.callback'))
            ->redirect();
    }

    public function callbackAuthGoogleDriveApi()
    {
        $user = Socialite::driver('google')
            ->scopes([
                'https://www.googleapis.com/auth/drive.metadata.readonly'
            ])
            ->with([
                'access_type' => 'offline',
            ])
            ->redirectUrl(route('admin.setting.drive.callback'))
            ->user();

        try {
            DB::beginTransaction();

            Setting::updateOrInsert([
                'key' => 'google_drive_refresh_token'
            ], [
                'value' => $user->refreshToken
            ]);

            DB::commit();

            return redirect()->route('admin.setting.edit', [ 'view' => 'drive' ])->with('success', 'Kết nối thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);
            return redirect()->route('admin.setting.edit', [ 'view' => 'drive' ])->withErrors('Kết nối thất bại.');
        }
    }
}
