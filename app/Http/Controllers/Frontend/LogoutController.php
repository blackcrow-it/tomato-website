<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Session;

class LogoutController extends Controller
{
    public function logout() {
        Auth::logout();
        Session::flush();

        return redirect()->route('home');
    }
}
