<?php

namespace App\Http\Controllers\Api;

use App\Helpers\SmsHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OperatorLoginRequest;
use App\Http\Requests\OperatorOtpVerifyRequest;
use App\Http\Requests\VerifyTokenRequest;
use App\Models\CheckInOutPlace;
use App\Models\User;
use Illuminate\Http\Response;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class QrCodeOperatorController extends Controller
{
    public function login(OperatorLoginRequest $request) {
        $user = User::where('phone_number', $request->phone_number)->first();
        if(!isset($user)) {
            return response()->json([
                'message' => 'Account does not exists',
                'status' => 'failure'
            ], Response::HTTP_BAD_REQUEST);
        }
        /* generating otp for user */
        $otp = (new Otp)->generate($user->phone_number, 'numeric', 4, 30);
        /* sending OTP to user */
            $isOtpSend = SmsHelper::sendOtpMsg($user->phone_number, $user->name, $otp->token);
             if (!$isOtpSend) {
                return response()->json([
                    'message' => 'Failed to send OTP',
                    'status' => 'failure'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $messageStr = $this->obfuscate_phone($user->phone_number);

        return response()->json([
            'message' => "OTP sent to your phone number $messageStr",
            'status' => 'success',
            'data' => [
                'phone' => $user->phone_number,
            ]
        ], Response::HTTP_OK);
    }

    public function obfuscate_phone($phone) {
        $hiddenPhoneNo = substr($phone, 0, 3) . '****' . substr($phone, -3);
        return $hiddenPhoneNo;
    }

    public function verifyOtp(OperatorOtpVerifyRequest $request)
    {
        $otp = $request->otp;
        $phone = $request->phone_number;

        $isOtpValid = (new Otp)->validate($phone, $otp);

        if (!$isOtpValid->status) {
            return response()->json([
                'message' => $isOtpValid->message,
                'status' => 'failure'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::where('phone_number', $request->phone_number)->first();

        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'OTP verified successfully',
            'status' => 'success',
            'data' => [
                'token' => $token,
                'user' => $user,
                'places' => CheckInOutPlace::all()
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
        return response()->json([
            "status" => "success",
            "message" => "Token exists",
            "data" => [
                'id' => $user->id,
                'name' => $user->name,
                "places" => CheckInOutPlace::all()
            ]
        ], Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $token = explode(' ', $request->header('authorization'));
        $isTokenExists = PersonalAccessToken::findToken($token[1]);
        if (!isset($token) || empty($isTokenExists)) {
            return response()->json([
                "message" => "Token are not set in headers / Token Expired",
                "status" => "failure"
            ], Response::HTTP_UNAUTHORIZED);
        }
        $member = $isTokenExists->tokenable;
        $member->tokens()->delete();
        return response()->noContent();
    }
}
