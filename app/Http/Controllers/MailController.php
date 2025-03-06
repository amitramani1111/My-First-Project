<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sentMail()
    {
        $toMail = 'ramaniamit640@gmail.com';
        $message = 'Test Mail from Laravel.';
        $subject = 'Testing Mail';
        $detailes = [
            'name' => 'Amit Ramani',
            'email' => $toMail,
            'role' => 'Admin',
        ];

        $mail = Mail::to($toMail)->send(new WelcomeMail($message, $subject, $detailes));

        dd($mail);
    }
}
