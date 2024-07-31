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
            'data' => $userData
        ], Response::HTTP_OK);
    }
    public function getUserDetailsTest()
    {
        $user = auth()->user();
        $memberData = Member::where('id', $user->id)->get();
        $memberRegData = [];
        if(Registration::where('member_id', $user->id)->exists()) {
            $memberRegData = Registration::with('familyDetails', 'purchaseDetails')->where('member_id', $user->id)->get()->first()->toArray();
        }
        $totMehramsChildrensFee = !empty($memberRegData['family_details']) ? array_sum(array_column($memberRegData['family_details'], 'fees')) : 0;
        return response()->json([
            'status' => 'success',
            'data' => [
                'member_data' => $memberData,
                'member_reg_data' => !empty($memberRegData) ? $memberRegData : [],
                'total_fees' =>  $totMehramsChildrensFee + ($memberRegData ? (int) $memberRegData['member_fees']: 0),
            ],
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
        $user = Member::find($user->id);
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
        $mehrams = $request->get('mehrams', []);
        $childrens = $request->get('childrens', []);

        $member_fee = in_array($user->zone_name, config('fees.special_states')) ? 3000 : 2000;
        $user->registration->update(['member_fees' => $member_fee]);
        // Mehrams
        if(!empty($mehrams) && count($mehrams) > 0) {
            if(isset($mehrams[0]['id'])) {
                $ids = array_column($mehrams, 'id');
                RegFamilyDetail::where('registration_id', $user->registration->id)->whereNotIn('id', $ids)->where('type', 'mehram')->delete();
            }
            // update or create mehrams
            foreach($mehrams as $mehram) {
                $mehram['fees'] = 600;
                RegFamilyDetail::updateOrCreate(['id' => $mehram['id'] ?? null, 'registration_id' => $user->registration->id,'type' => 'mehram'], $mehram);
            }
        } else {
            RegFamilyDetail::where('registration_id', $user->registration->id)->where('type', 'mehram')->delete();
        }
        // Childrens
        if(!empty($childrens) && count($childrens) > 0) {
            if(isset($childrens[0]['id'])) {
                $ids = array_column($childrens, 'id');
                RegFamilyDetail::where('registration_id', $user->registration->id)->whereNotIn('id', $ids)->where('type', 'children')->delete();
            }
            // update or create childrens
            foreach($childrens as $children) {
                $children['fees'] = $children['age'] < 7  ? 0 : 300;
                RegFamilyDetail::updateOrCreate(['id' => $children['id'] ?? null, 'registration_id' => $user->registration->id, 'type' => 'children'], $children);
            }
        } else {
            RegFamilyDetail::where('registration_id', $user->registration->id)->where('type', 'children')->delete();
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Family details updated successfully',
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
            'message' => 'details updated successfully',
        ], Response::HTTP_OK);
    }

    // Update financial details
    public function updateFinancialDetails(Request $request) {
        $user = auth()->user();
        $request->validate([
            'fees_paid_to_ameer' => 'required'
        ]);
        $user->registration->update(['fees_paid_to_ameer' => $request->get('fees_paid_to_ameer')]);
        return response()->json([
            'status' => 'success',
            'message' => 'Financial details updated successfully',
        ], Response::HTTP_OK);
    }
}
