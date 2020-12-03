<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.contact.index');
    }

    public function submit(Request $request)
    {
        Mail::to(config('settings.email_notification'))->send(new ContactMail($request->input()));
    }
}
