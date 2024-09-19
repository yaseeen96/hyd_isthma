<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class AdminDataFetchController extends Controller
{
    public function getZoneNames(Request $request) {
        $unit_name = $request->input('unit_name');
        $zone_name = Member::where('unit_name', $unit_name)->pluck('zone_name')->first();
        $zone_name = Member::distinct()->pluck('zone_name');
        return response()->json(['zone_name' => $zone_name]);
    }
}