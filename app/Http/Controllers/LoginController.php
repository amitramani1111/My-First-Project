<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function showLogin()
    {
        if ((Auth::guard('admins')->check()) && (auth()->user()->otp != null) && (auth()->user()->status == 'online')) {
            return redirect()->route('index');
        } else {
            return view('admin.auth.login');
        }
    }

    public function showRegister()
    {
        return view('admin.auth.register');
    }
    // Register
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins,email',
            'mobile' => 'required|size:10|unique:admins,mobile',
            'password' => 'required|confirmed',
            'role' => 'required'
        ]);

        $admin = Admin::create($data);

        if ($admin) {
            return redirect()->route('login');
        }
    }

    public function otpPage(string $id)
    {
        $user = $id;
        if ((Auth::guard('admins')->check()) && (auth()->user()->otp != null) && (auth()->user()->status == 'online')) {
            return redirect()->route('index');
        } else if ((Auth::guard('admins')->check()) && (auth()->user()->otp != null) && (auth()->user()->status == 'offline')) {
            return view('admin.auth.otp', compact('user'));
        } else {
            return redirect()->route('login');
        }
    }

    private function randomeOtp()
    {
        return rand('1000', '9999');
    }

    // Login
    public function login(Request $request)
    {

        $email_Number = $request->input('email');
        $admin = Admin::where('email', $email_Number)->orWhere('mobile', $email_Number)->first();

        if (!$admin) {
            return redirect()->back()->withErrors(['email' => 'Please Enter Valid Email or Number']);
        }

        $request->validate([
            'password' => 'required',
            // 'password' => 'required|same:password|min:8',
        ]);

        if (
            Auth::guard('admins')->attempt(['email' => $admin->email, 'password' => $request->password]) ||
            Auth::guard('admins')->attempt(['mobile' => $admin->mobile, 'password' => $request->password])
        ) {
            $otp = $this->randomeOtp();
            $setOtp = Admin::where('id', $admin->id)->update(['otp' => $otp]);
            $userEmail = auth()->user()->email;
            Mail::to($userEmail)->send(new OtpMail($otp));
            // Auth::loginUsingId($admin->id);
            $user_id = $admin->id;
            Auth::loginUsingId($user_id);
            return redirect('otp/' . $user_id);
        } else {
            return redirect()->back()->withErrors(['password' => 'Please Enter Valid Password']);
        }
    }

    // OTP Match
    public function otpMatch(Request $request)
    {
        $currendID = $request->id;
        $currendOTP = $request->otp;


        if (($currendID == auth()->user()->id) && (auth()->user()->otp == $currendOTP) && (auth()->user()->status == 'offline')) {
            $setStatus = Admin::where('id', $currendID)->update(['status' => 'online']);
            if ($setStatus) {
                return redirect()->route('index');
            } else {
                return redirect()->route('otp');
            }
        } else {
            return redirect('otp/' . $currendID);
        }
    }


    // Go to Home Page
    public function indexPage()
    {
        if ((Auth::guard('admins')->check()) && (auth()->user()->otp != null) && (auth()->user()->status == 'online')) {
            return view('index');
        } else if ((Auth::guard('admins')->check()) && (auth()->user()->otp != null) && (auth()->user()->status == 'offline')) {
            $user = auth()->user()->id;
            return redirect('/otp/'. $user);
        } else {
            return redirect()->route('login');
        }
    }

    // Go to Logout Page
    public function logout()
    {
        $id = auth()->user()->id;
        $setStatus = Admin::where('id', $id)->update(['otp' => null, 'status' => 'offline']);
        if ($setStatus) {
            Auth::logout();
            return redirect()->route('login');
        }
    }

}
