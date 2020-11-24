<?php

namespace App\Http\Controllers;

use App\Mail\EpayRechargeMail;
use Auth;
use Debugbar;
use Mail;

class TestController extends Controller
{
    public function index()
    {
        Debugbar::disable();

        if (config('settings.email_notification')) {
            Mail::to('vipboysanhdieu@gmail.com')
                ->send(
                    new EpayRechargeMail([
                        'user' => Auth::user(),
                        'amount' => 500000
                    ])
                );
        }
    }
}
