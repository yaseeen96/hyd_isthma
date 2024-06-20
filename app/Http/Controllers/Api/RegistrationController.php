<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RegistrationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userData = Member::with('registration')->where('id', $user->id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $userData,

        ], Response::HTTP_OK);

    }

    public function register(Request $request)
    {
        $user = auth()->user();
        $regsData = [
            "member_id" => $user->id,
            "confirm_arrival" => $request->get('confirm_arrival'),
            "reason_for_not_coming" => $request->get('reason_for_not_coming'),
            "ameer_permission_taken" => $request->get('ameer_permission_taken'),
            "emergency_contact" => $request->get('emergency_contact'),
        ];
        if (!empty($request->input('dob'))) {
            $user->update(['dob' => $request->get('dob')]);
        }
        Registration::updateOrCreate(['member_id' => $user->id], $regsData);
        $data = Member::with('registration')->where('id', $user->id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], Response::HTTP_OK);
    }
}