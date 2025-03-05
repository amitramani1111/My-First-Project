<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sentMail()
    {
        $email = 'kakadiyatrupesh620@gmail.com';
        $message = 'maro aa le';
        $subject = 'Email';
        $sendEmail = Mail::to($email)->send(new WelcomeMail($message, $subject));
        dd($sendEmail);
    }
}
