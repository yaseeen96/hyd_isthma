<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\checkInOutEntires;
use App\Models\Member;
use App\Models\QrBatchRegistration;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class checkInOutEntiresController extends Controller
{
    public function bulkUpload(Request $reqest) {

        $user = auth()->user();
        $data = $reqest->all();
        if(count($data) > 0) {

            $name = '';
            $zone_name = '';
            $division_name = '';
            $unit_name = '';
            $gender = '';
            $phone_number = '';
            foreach($data as $entry) {
                $userData = '';
                if($entry['category'] == 'Rukun') {
                    $userData = Member::where('user_number', $entry["id"])->get()->first();
                    $name = $userData->name;
                    $phone_number = $userData->phone;
                } else {
                    $userData = QrBatchRegistration::where('batch_id', $entry['id'])->get()->first();
                    $name = $userData->full_name;
                    $userData = $userData->phone_number;
                }
                $zone_name = $userData->zone_name;
                $division_name = $userData->division_name;
                $unit_name = $userData->unit_name;
                $gender = $userData->gender;
                $entryData = [
                    'batch_id' => $entry['id'],
                    'batch_type' => $entry['category'],
                    'gender' => $gender,
                    'place_id' => $entry['placeId'],
                    'date' => $entry['date'],
                    'time' => $entry['time'],
                    'mode' => $entry['mode'],
                    'name' => $name,
                    'zone_name' => $zone_name,
                    'division_name' => $division_name,
                    'unit_name' => $unit_name,
                    'phone_number' => $phone_number,
                    // 'operator_id' => $user->id,
                    'operator_id' => 1,
                ];
                checkInOutEntires::create($entryData);
            }
            return response()->json([
                'message' => 'Data saved successfully',
                'status' => 'success'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No data found',
                'status' => 'failure'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}