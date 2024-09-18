<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\checkInOutEntires;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class checkInOutEntiresController extends Controller
{
    public function bulkUpload(Request $reqest) {
        $user = auth()->user();
        $data = $reqest->all();
        if(count($data) > 0) {
            foreach($data as $entry) {
                $entryData['user_id'] = $entry['id'];
                $entryData['type'] = $entry['type'];
                $entryData['rukun_id'] = $entry['ruknId'];
                $entryData['name'] = $entry['name'];
                $entryData['age'] = $entry['age'];
                $entryData['gender'] = $entry['gender'];
                $entryData['unit'] = $entry['unit'];
                $entryData['halqa'] = $entry['halqa'];
                $entryData['company_name'] = $entry['companyName'];
                $entryData['department_name'] = $entry['departmentName'];
                $entryData['govt_organization'] = $entry['govtOrganization'];
                $entryData['place_id'] = $entry['placeId'];
                $entryData['datetime'] = $entry['time'];
                $entryData['mode'] = $entry['mode'];
                $entryData['operator_id'] = $user->id;
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