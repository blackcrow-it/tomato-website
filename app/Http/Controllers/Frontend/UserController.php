<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\UserInfoRequest;
use Auth;
use DB;
use Illuminate\Http\Request;

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
}
