<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Setting;

class HomeController extends Controller
{
    public function index()
    {
        $consultationFormBg = Setting::where('key', 'consultation_background')->first();
        return view('frontend.home.index', [
            'consultation_background' => $consultationFormBg->value,
        ]);
    }
}
