<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Mail\ContactMail;
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

        return new ContactMail([
            "name" => "Le Tuan Anh",
            "phone" => "0376287231",
            "email" => "vipboysanhdieu@gmail.com",
            "course" => "Khóa Học Tiếng Trung",
            "content" => "aaaaaaa"
        ]);
    }
}
