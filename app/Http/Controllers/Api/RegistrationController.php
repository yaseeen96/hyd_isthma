<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\RegFamilyDetail;
use App\Models\Registration;
use App\Models\RegPurchasesDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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
         if (!empty($request->input('email'))) {
            $user->update(['email' => $request->get('email')]);
        }

        Registration::updateOrCreate(['member_id' => $user->id], $regsData);
        $data = Member::with('registration')->where('id', $user->id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], Response::HTTP_OK);
    }

    // update mehrams details
    public function updateFamilyDetails(Request $request) {
        $user = auth()->user();   
        $mehrams = $request->get('mehrams');
        $childrens = $request->get('childrens');
        if(isset($mehrams) && count($mehrams) > 0) {
            foreach($mehrams as $mehram) {
                RegFamilyDetail::updateOrCreate(['registration_id' => $user->registration->id, 'type' => 'mehram', 'name' => $mehram['name']], $mehram);
            }
        }
        if(isset($childrens) && count($childrens) > 0) {
            foreach($childrens as $children) {
                RegFamilyDetail::updateOrCreate(['registration_id' => $user->registration->id, 'type' => 'children', 'name' => $children['name']], $children);
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Family details updated successfully',
        ], Response::HTTP_OK);
    }

    public function updateFinancialDetails(Request $request) {
        $user = auth()->user();

        $memberFees = $request->get('member_fees');
        $mehrams = $request->get('mehrams');
        $childrens = $request->get('childrens');
        if(isset($mehrams) && count($mehrams) > 0) {
            foreach($mehrams as $mehram) {
                RegFamilyDetail::updateOrCreate(['registration_id' => $user->registration->id, 'type' => 'mehram', 'name' => $mehram['name']], $mehram);
            }

            if(isset($childrens) && count($childrens) > 0) {
            foreach($childrens as $children) {
                RegFamilyDetail::updateOrCreate(['registration_id' => $user->registration->id, 'type' => 'children', 'name' => $children['name']], $children);
            }
            }
        }
        $registration = Registration::where('member_id', $user->id)->first();
        $registration->update(['member_fees' => $memberFees]);
        return response()->json([
            'status' => 'success',
            'message' => 'Financial details updated successfully',
        ], Response::HTTP_OK);
    }

    public function updateAdditionalDetails(Request $request) {
        $user = auth()->user();
        $arrival = $request->get('arrival_details');
        $departure = $request->get('departure_details');
        $hotelRequired = $request->get('hotel_required');
        $specialConsiderations = $request->get('special_considerations');
        $sightSeeing = $request->get('sight_seeing');
        $healthConcern = $request->get('health_concern');
        $managementExperience = $request->get('management_experience');
        $purchasesRequired = $request->get('purchases_required');
        if(isset($purchasesRequired) && count($purchasesRequired) > 0) {
            foreach($purchasesRequired as $purchase) {
                RegPurchasesDetail::updateOrCreate(['registration_id' => $user->registration->id, 'type' => $purchase['name']], $purchase);
            }
        }
        $comments = $request->get('comments');
        $registration = Registration::where('member_id', $user->id)->first();
        $registration->update([
            "arrival_details" => $arrival,
            "departure_details" => $departure,
            "hotel_required" => $hotelRequired,
            "special_considerations" => $specialConsiderations,
            "sight_seeing" => $sightSeeing,
            "health_concern" => $healthConcern,
            "management_experience" => $managementExperience,
            "comments" => $comments,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Financial details updated successfully',
        ], Response::HTTP_OK);
    }
}