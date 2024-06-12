<?php

namespace App\Http\Controllers\Api;

use App\Helpers\SmsHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OtpVerifyRequest;
use App\Http\Requests\MemberLoginRequest;
use App\Http\Requests\VerifyTokenRequest;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Ichtrojan\Otp\Otp;

class AuthController extends Controller
{
    public function login(MemberLoginRequest $request)
    {


        $member = Member::where('phone', $request->phone)->first();
        // Auth::login($member);

        /* generating otp for user */
        $otp = (new Otp)->generate($member->phone, 'numeric', 4, 30);
        /* sending OTP to user */
        $isOtpSend = SmsHelper::sendOtpMsg($member->phone, $member->name, $otp->token);

        if (!$isOtpSend) {
            return response()->json([
                'message' => 'Failed to send OTP',
                'status' => 'failure'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $phone = $member->phone;
        $hiddenPhoneNo = substr($phone, 0, 3) . '****' . substr($phone, -3);

        return response()->json([
            'message' => 'OTP sent to your phone number ' . $hiddenPhoneNo,
            'status' => 'success',
            'data' => [
                'phone' => $member->phone
            ]
        ], Response::HTTP_OK);

    }

    public function verifyOtp(OtpVerifyRequest $request)
    {
        $otp = $request->otp;
        $phone = $request->phone;

        $isOtpValid = (new Otp)->validate($phone, $otp);

        if (!$isOtpValid->status) {
            return response()->json([
                'message' => $isOtpValid->message,
                'status' => 'failure'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $member = Member::where('phone', $phone)->first();

        $member->tokens()->delete();

        $token = $member->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'OTP verified successfully',
            'status' => 'success',
            'data' => [
                'token' => $token,
                'user' => $member,
            ]
        ], Response::HTTP_OK);
    }

    public function verifyToken(VerifyTokenRequest $request)
    {
        $token = $request->token;

        $isTokenExists = PersonalAccessToken::findToken($token);
        if (!isset($isTokenExists)) {
            return response()->json([
                "message" => "Token not exists",
                "status" => "failure"
            ], Response::HTTP_UNAUTHORIZED);
        }
        $user = $isTokenExists->tokenable;
        $IsRegDone = $user->registration;
        return response()->json([
            "status" => "success",
            "message" => "Token exists",
            "data" => [
                'id' => $user->id,
                'name' => $user->name,
                'confirm_arrival' => isset($IsRegDone) ? $user->registration->confirm_arrival : null
            ]
        ], Response::HTTP_OK);
    }
}