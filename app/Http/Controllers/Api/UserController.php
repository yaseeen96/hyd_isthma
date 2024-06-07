<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return response()->json([
            'status' => 'success',
            'data' => $user,
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        dd($user);
        $data = [
            "confirm_arrival" => $request->get('confirm_arrival'),
            "reason_for_not_coming" => $request->get('reason_for_not_coming'),
            "ameer_permission_taken" => $request->get('ameer_permission_taken'),
            "emergency_contact" => $request->get('emergency_contact')
        ];
        $user->update($data);

        return response()->json([
            'status' => 'success',
            'data' => $user,
        ], Response::HTTP_OK);
    }
}