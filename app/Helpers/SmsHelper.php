<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class SmsHelper
{
    public static function sendOtpMsg($mobile, $name, $otp)
    {
        $client = new Client();
        $response = $client->request('GET', env('TEXTLOCAL_APIURL'), [
            'query' => [
                'apikey' => env('TEXTLOCAL_APIKEY'),
                'numbers' => $mobile,
                'sender' => env('TEXTLOCAL_SENDER'),
                'message' => 'Dear ' . $name . ', Your OTP for login to the JIH portal is ' . $otp . '. Valid for 30 minutes. Please do not share this OTP. Regards, JIHHRD.com'
            ]
        ]);
        if ($response->getStatusCode() == 200) {
            return true;
        } else {
            return false;
        }
    }

}