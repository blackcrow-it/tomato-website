<?php

namespace App\Http\Controllers\Frontend;

use App\Constants\ObjectType;
use App\Constants\RechargeStatus;
use App\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ChangepassRequest;
use App\Http\Requests\Frontend\UploadAvatarRequest;
use App\Http\Requests\Frontend\UserInfoRequest;
use App\InvoiceItem;
use App\Recharge;
use App\UserCourse;
use Auth;
use DB;
use Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Storage;

class UserController extends Controller
{
    public function info()
    {
        redirect()->setIntendedUrl(route('user.info'));

        return view('frontend.user.info');
    }

    public function info_getData()
    {
        return Auth::user();
    }

    public function info_submitData(UserInfoRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = Auth::user();
            $user->fill($request->input());
            $user->save();
        });
    }

    public function invoice()
    {
        $invoiceItems = InvoiceItem::whereHas('invoice', function (Builder $query) {
            $query->where('user_id', Auth::user()->id);
        })
            ->orderBy('created_at', 'desc')
            ->paginate();

        $invoiceItems->getCollection()->transform(function ($item) {
            $item->object = null;
            switch ($item->type) {
                case ObjectType::COURSE:
                    $item->object = Course::with('category')->find($item->object_id);
                    break;
            }
            return $item;
        });

        return view('frontend.user.invoice', [
            'invoice_items' => $invoiceItems
        ]);
    }

    public function myCourse()
    {
        $userCourses = UserCourse::with([
            'course' => function ($query) {
                $query->where('enabled', true);
            }
        ])
            ->where('user_id', Auth::user()->id)
            ->where('expires_on', '>', now())
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('frontend.user.my_course', [
            'user_courses' => $userCourses
        ]);
    }

    public function uploadAvatar(UploadAvatarRequest $request)
    {
        $user = Auth::user();

        $file = $request->file('avatar');
        $path = $file->storePubliclyAs('avatars', $user->id . '.png', 's3');

        $avatarUrl = Storage::disk('s3')->url($path);

        $user->avatar = $avatarUrl . '?t=' . time();
        $user->save();

        return $user->avatar;
    }

    public function recharge()
    {
        return view('frontend.user.recharge');
    }

    public function rechargeHistory()
    {
        $data = Recharge::where('user_id', Auth::user()->id)
            ->where('status', RechargeStatus::SUCCESS)
            ->orderBy('updated_at', 'desc')
            ->paginate();

        $data->getCollection()->transform(function($item) {
            return [
                'amount' => $item->amount,
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $item->updated_at->format('Y-m-d H:i:s'),
                'type' => $item->type,
            ];
        });

        return $data;
    }

    public function changepass()
    {
        return view('frontend.user.changepass');
    }

    public function doChangepass(ChangepassRequest $request)
    {
        $user = Auth::user();
        $user->password = Hash::make($request->input('new_pass'));
        $user->save();

        return redirect()->route('user.changepass')->with('success', 'Thay đổi mật khẩu thành công.');
    }
}
