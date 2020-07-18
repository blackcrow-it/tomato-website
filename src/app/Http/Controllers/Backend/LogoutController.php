<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout() {
        Auth::logout();

        return redirect()->route('admin.login');
    }
}
