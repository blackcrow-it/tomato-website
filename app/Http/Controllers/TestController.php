<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Mail\InvoiceMail;
use Auth;
use Debugbar;
use Mail;

class TestController extends Controller
{
    public function index()
    {
        Debugbar::disable();

        Mail::to(config('settings.email_notification'))
            ->send(
                new InvoiceMail([
                    'invoice' => Invoice::find(15)
                ])
            );
    }
}
