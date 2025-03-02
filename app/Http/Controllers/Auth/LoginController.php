<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Jobs\sendOtpEmail;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function sendOtp(Request $request){
        $validator = Validator::make($request->input(),[
            'email'   => 'required',
            'password' => 'required'
        ]);
        $status = "";
        $message = "";
        $callFn = "";
        if($validator->fails()){
            $status = 0;
            $message =  $validator->errors()->first();
        } else {
            $credentials = $request->only('email', 'password');
            if (!Auth::attempt($credentials)) {
                $status = 0;
                $message = "Username or Password Incorrect";
            } else {
                $userId = Auth::user()->id;
                Auth::logout();
                $status = 1;
                $otp = random_int(100000, 999999);
                sendOtpEmail::dispatch($request->email, $otp);
                $encryptOtp = encrypt($otp);
                $encryptOtp = json_encode(encrypt($otp));
                $message = "OTP Sent to Email";
                $callFn = "showOtpForm($userId, $encryptOtp);";
            }
        }
        return response()->json([
            'status'  => $status,
            'message' => $message,
            'callFn'  => $callFn
        ]);
    }

    public function verifyOtp(Request $request){
        $validator = Validator::make($request->input(),[
            'otp'   => 'required|integer'
        ]);
        $status = "";
        $message = "";
        $redirect = "";
        if($validator->fails()){
            $status = 0;
            $message =  $validator->errors()->first();
        } else {
            $userId = $request->user_id;
            $otp = $request->otp;
            $validOtp = decrypt($request->valid_otp);
            if($validOtp != $otp){
                $status = 0;
                $message =  "OTP is wrong";
            } else {
                Auth::loginUsingId($userId, TRUE);
                $status = 1;
                $message =  "OTP Verified";
                $redirect = url('home');
            }
        }
        return response()->json([
            'status'   => $status,
            'message'   => $message,
            'redirect'  => $redirect
        ]);
    }

    public function logout(){
        Auth::logout();
        return redirect()->back();
    }
}
