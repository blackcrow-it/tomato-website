<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Mail\ExceptionMail;
use App\Mail\InvoiceMail;
use Auth;
use Debugbar;
use Exception;
use Mail;

class TestController extends Controller
{
    public function index()
    {
        Debugbar::disable();
    }
}
