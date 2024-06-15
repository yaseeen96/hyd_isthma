<?php

namespace App\Http\Controllers;

use App\Helpers\SmsHelper;
use App\Models\Member;
use Illuminate\Http\Request;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\Auth;

class DeleteAccountController extends Controller
{
    protected $previous;
    public function __construct()
    {
        $this->middleware('auth:member')->except(['login', 'otpVerify', 'loginWithOtp']);
    }
    public function index(Request $request)
    {
        return view('delete.index');
    }

    public function login()
    {
        return view('delete.login');
    }

    public function loginWithOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'numeric']
        ]);
        $user = Member::where(['phone' => $request->phone, 'status' => 'Active'])->first();
        if (!empty($user)) {
            $otp = (new Otp)->generate($user->phone, 'numeric', 4, 30);
            $isOtpSend = SmsHelper::sendOtpMsg($user->phone, $user->name, $otp->token);
            return $isOtpSend;
        } else {
            return response()->json([
                'errors' => ['phone' => 'Acccount does not exists. Please register a new account']
            ], 422);
        }
    }

    public function otpVerify(Request $request)
    {
        $otp = $request->otp;
        $phone = $request->phone;
        $isOtpValid = (new Otp)->validate($phone, $otp);

        if (!$isOtpValid->status) {
            return response()->json([
                'errors' => ['otp' => 'Invalid OTP/Credentials']
            ], 422);
        }
        $member = Member::where('phone', $phone)->first();
        Auth::guard('member')->loginUsingId($member->id);

        return response()->json([
            'login' => 'success',
            'intended' => $this->previous ? $this->previous : route('delete-account'),
        ]);

    }

    public function delete(Request $request)
    {
        $user = Auth::guard('member')->user();
        $user->status = 'InActive';
        $user->update();
        return redirect(route('tmp-login'));
    }

    protected function guard()
    {
        return Auth::guard('member');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect()->route('tmp-login');
    }
}