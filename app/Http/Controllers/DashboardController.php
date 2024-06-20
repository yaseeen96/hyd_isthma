<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Registration;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public $totArkansMems = 0;
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $this->totArkansMems = Member::count();
        $totRegistered = Registration::count();
        $totAttendees = Registration::where('confirm_arrival', 1)->count();
        $totNonAttendees = Registration::where('confirm_arrival', 0)->count();


        // chartjs statistics
        $query = $this->fetchFilterData($request);
        $filterData = [
            "registrations" => [
                'labels' => ['Registered', 'Not Registered'],
                'data' => [$query->count(), $this->totArkansMems - $query->count()],
            ],
            "attendees" => [
                'labels' => ['Total Attendees', 'Total Non Attendees'],
                'data' => [$query->where('confirm_arrival', 1)->count(), $query->where('confirm_arrival', 0)->count()],
            ]
        ];
        if ($request->ajax()) {
            if (empty($request->has('unit_name')) && empty($request->has('zone_name')) && empty($request->has('division_name'))) {
                return ['filterData' => $filterData];
            }
            $query = $this->fetchFilterData($request);
            return [
                'filterData' => [
                    "registrations" => [
                        'labels' => ['Registered', 'Not Registered'],
                        'data' => [$query->count(), $this->totArkansMems - $query->count()],
                    ],
                    "attendees" => [
                        'labels' => ['Total Attendees', 'Total Non Attendees'],
                        'data' => [$query->where('confirm_arrival', 1)->count(), $query->where('confirm_arrival', 0)->count()],
                    ]
                ]
            ];
        }
        return view('admin.dashboard')->with([
            'totArkansMems' => $this->totArkansMems,
            'totRegistered' => $totRegistered,
            'totAttendees' => $totAttendees,
            'totNonAttendees' => $totNonAttendees,
            'filterData' => $filterData
        ]);
    }

    private function fetchFilterData($request)
    {
        $unit_name = $request->get('unit_name');
        $zone_name = $request->get('zone_name');
        $division_name = $request->get('division_name');

        $query = Registration::with('member')
            ->whereHas('member', function ($q) use ($unit_name, $zone_name, $division_name) {
                isset($unit_name) ? $q->where('unit_name', $unit_name) : $q;
                isset($zone_name) ? $q->where('zone_name', $zone_name) : $q;
                isset($division_name) ? $q->where('division_name', $division_name) : $q;
            })->get();

        $this->totArkansMems = Member::where(function ($q) use ($unit_name, $zone_name, $division_name) {
            isset($unit_name) ? $q->where('unit_name', $unit_name) : $q;
            isset($zone_name) ? $q->where('zone_name', $zone_name) : $q;
            isset($division_name) ? $q->where('division_name', $division_name) : $q;
        })->get()->count();
        return $query;
    }

    public function getDivisions(Request $request)
    {
        $zone_name = $request->input('zone_name');
        $division_name = Member::select('division_name')->distinct()->where('zone_name', $zone_name)->get();
        return response()->json(['division_name' => $division_name]);
    }

    public function getUnits(Request $request)
    {

        $division_name = $request->input('division_name');
        $unit_name = Member::select('unit_name')->distinct()->where('division_name', $division_name)->get();
        return response()->json(['unit_name' => $unit_name]);

    }

}