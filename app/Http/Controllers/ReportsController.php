<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelperFunctions;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\RegFamilyDetail;
use App\Models\RegPurchasesDetail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ReportsController extends Controller
{
    public function familyDetailsReport(Request $request, DataTables $datatables)
    {
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('View FamilyDetailsReport')){
            abort(403);
        }
        if($request->ajax())
        {
            $query = RegFamilyDetail::with('registration', 'registration.member')->where(function ($query) use ($request) {
                if (isset($request->gender))
                    $query->where('gender', $request->gender);
                if (isset($request->age_group))
                    $query->where('type', $request->age_group);
                if (isset($request->interested_in_volunteering)) {
                    $interested_in_volunteering = $request->interested_in_volunteering == 'null' ? null : $request->interested_in_volunteering;
                    $query->where('interested_in_volunteering', $interested_in_volunteering);
                }
            })->where(function ($query) use($request) {
                if(isset($request->zone_name)){
                    $query->whereHas('registration.member', function ($q) use ($request) {
                        $q->where('zone_name', $request->zone_name);
                    });
                }
                if(isset($request->division_name)){
                    $query->whereHas('registration.member', function ($q) use ($request) {
                        $q->where('division_name', $request->division_name);
                    });
                }
                if(isset($request->unit_name)){
                    $query->whereHas('registration.member', function ($q) use ($request) {
                        $q->where('unit_name', $request->unit_name);
                    });
                }
                $query->whereHas('registration.member', function ($q) use ($request) {
                    $q->filterByZone();
                });
            })->orderBy('id', 'asc');
            return $datatables->eloquent($query)
                    ->editColumn('interested_in_volunteering', function(RegFamilyDetail $familyDetail) {
                    return ($familyDetail->interested_in_volunteering == null ? '' : ($familyDetail->interested_in_volunteering == 'yes' ? AppHelperFunctions::getGreenBadge('Yes') : AppHelperFunctions::getRedBadge('No')));
                    })
                    ->rawColumns(['interested_in_volunteering'])->addIndexColumn()->make(true);
        }
        return view('admin.reports.family-details-report');
    }
    public function paymentDetailsReport(Request $request, DataTables $datatable)
    {
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('View PaymentDetailsReport')){
            abort(403);
        }
        if($request->ajax())
        {
            $query = Registration::with('member')->where(function ($query) use($request) {
                    if(isset($request->paid_status)) {
                        $condition = $request->paid_status == 'no' ? '=' : '!=';
                        $query->where('fees_paid_to_ameer', $condition, null);
                    }
             })->whereHas('member', function ($query) use ($request) {
                if (isset($request->unit_name)) {
                    $query->where('unit_name', $request->unit_name);
                }
                if (isset($request->zone_name)) {
                    $query->where('zone_name', $request->zone_name);
                }
                if (isset($request->division_name)) {
                    $query->where('division_name', $request->division_name);
                }
                $query->filterByZone();
            });
            return $datatable->eloquent($query)
                ->addColumn('member_fees', function (Registration $registration) {
                        $memberFee = $registration->member_fees ?? 0;
                        $familyFee = RegFamilyDetail::where('registration_id', $registration->id)->sum('fees');
                        return $memberFee + $familyFee;
                    })
                ->rawColumns(['member_fees'])
                ->with('sum_total_fees', $query->sum('member_fees'))->addIndexColumn()->make(true);
        }
        return view('admin.reports.payment-details-report');
    }
    public function arrivalReport(Request $request, DataTables $dataTables) {

        $user = User::find(auth()->user()->id);
        if (auth()->user()->id != 1 && !$user->hasPermissionTo('View TravelReport')){
            abort(403);
        }
        if($request->ajax()) {
            $query = Registration::with('member')->whereHas('member', function ($query) use ($request) {
                if (!empty($request->unit_name)) {
                    $query->where('unit_name', $request->unit_name);
                }
                if (!empty($request->zone_name)) {
                    $query->where('zone_name', $request->zone_name);
                }
                if (!empty($request->division_name)) {
                    $query->where('division_name', $request->division_name);
                }
                $query->filterByZone();
            })->select('registrations.*')->where([['confirm_arrival', '=', 1], ['arrival_details', '!=', '']])
                ->where(function ($query) use($request) {
                    if(!empty($request->date_time)){
                        $query->where('arrival_details->datetime', 'like', "%{$request->date_time}%");
                    }

                    if(!empty($request->travel_mode)) {
                        $query->where('arrival_details->mode', 'like', "%{$request->travel_mode}%");
                    }

                    if(!empty($request->end_point)){
                        $query->where('arrival_details->end_point', 'like', "%{$request->end_point}%");
                    }

                    if(!empty($request->mode_identifier)) {
                        $query->where('arrival_details->mode_identifier', 'like', "%{$request->mode_identifier}%");
                    }
                })->orderBy('id', 'asc');
            return $dataTables->eloquent($query)
                ->addColumn('travel_mode', function (Registration $registration) {
                    return $registration->arrival_details['mode'] ?? 'NA';
                })
                ->addColumn('date_time', function (Registration $registration) {
                    return $registration->arrival_details['datetime'] ?
                        '<span class="badge badge-success">' . date('Y-m-d', strtotime($registration->arrival_details['datetime'])) . '</span>' : 'NA';
                    })
                ->addColumn('end_point', function (Registration $registration) {
                    return $registration->arrival_details['end_point'] ?
                        $registration->arrival_details['end_point'] : 'NA';
                    })
                ->addColumn('mode_identifier', function (Registration $registration) {
                    return $registration->arrival_details['mode_identifier'] ?
                        $registration->arrival_details['mode_identifier'] : 'NA';
                    })
                ->addColumn('total_family_members', function (Registration $registration) {
                    return $registration->familyDetails->count();
                    })
                ->rawColumns(['travel_mode', 'date_time' , 'end_point', 'mode_identifier', 'total_family_members'])
                    ->addIndexColumn()->make(true);
        }
        return view('admin.reports.arrival-report');
    }
    public function departureReport(Request $request, DataTables $dataTables) {

        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('View TravelReport')){
            abort(403);
        }

        if($request->ajax()) {
            $query = Registration::with('member')->whereHas('member', function ($query) use ($request) {
                if (!empty($request->unit_name)) {
                    $query->where('unit_name', $request->unit_name);
                }
                if (!empty($request->zone_name)) {
                    $query->where('zone_name', $request->zone_name);
                }
                if (!empty($request->division_name)) {
                    $query->where('division_name', $request->division_name);
                }
                $query->filterByZone();
            })->select('registrations.*')->where([['confirm_arrival', '=', 1], ['departure_details', '!=', '']])
                ->where(function ($query) use($request) {
                    if(!empty($request->date_time)){
                        $query->where('departure_details->datetime', 'like', "%{$request->date_time}%");
                    }

                    if(!empty($request->travel_mode)) {
                        $query->where('departure_details->mode', 'like', "%{$request->travel_mode}%");
                    }

                    if(!empty($request->start_point)){
                        $query->where('departure_details->start_point', 'like', "%{$request->start_point}%");
                    }

                    if(!empty($request->mode_identifier)) {
                        $query->where('departure_details->mode_identifier', 'like', "%{$request->mode_identifier}%");
                    }
                })->orderBy('id', 'asc');
            return $dataTables->eloquent($query)
                ->addColumn('travel_mode', function (Registration $registration) {
                    return $registration->departure_details['mode'] ?? 'NA';
                })
                ->addColumn('date_time', function (Registration $registration) {
                    return $registration->departure_details['datetime'] ?
                        '<span class="badge badge-success">' . date('Y-m-d', strtotime($registration->departure_details['datetime'])) . '</span>' : 'NA';
                    })
                ->addColumn('start_point', function (Registration $registration) {
                    return $registration->departure_details['start_point'] ?? 'NA';
                    })
                ->addColumn('mode_identifier', function (Registration $registration) {
                    return $registration->departure_details['mode_identifier'] ?
                        $registration->departure_details['mode_identifier'] : 'NA';
                    })
                ->addColumn('total_family_members', function (Registration $registration) {
                    return $registration->familyDetails->count();
                    })
                ->rawColumns(['travel_mode', 'date_time' , 'start_point', 'mode_identifier', 'total_family_members'])
                    ->addIndexColumn()->make(true);
        }
        return view('admin.reports.departure-report');
    }
    public function commonDataReport(Request $request, DataTables $dataTables) {

        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('View CommonDataReport')){
            abort(403);
        }
        if($request->ajax()) {
            $query = Registration::with('member')->whereHas('member', function ($query) use ($request) {
                if (!empty($request->unit_name)) {
                    $query->where('unit_name', $request->unit_name);
                }
                if (!empty($request->zone_name)) {
                    $query->where('zone_name', $request->zone_name);
                }
                if (!empty($request->division_name)) {
                    $query->where('division_name', $request->division_name);
                }
                $query->filterByZone();
            })->select('registrations.*')->where([['confirm_arrival', '=', 1]])
                ->where(function ($query) use($request) {
                    if(!empty($request->hotel_required)){
                        $query->where('hotel_required', $request->hotel_required);
                    }
                    if(!empty($request->need_attendant)) {
                        $query->where('special_considerations->need_attendant', $request->need_attendant);
                    }
                    if(!empty($request->cot_or_bed)){
                        $query->where('special_considerations->cot_or_bed', $request->cot_or_bed);
                    }
                    if(!empty($request->health_concern)) {
                        $condition = $request->health_concern == 'yes' ? '!=' : '=';
                        $query->where('health_concern', $condition, null);
                    }
                    if(!empty($request->management_experience)) {
                        $query->where('management_experience', $request->management_experience);
                    }
                })->orderBy('id', 'asc');
            return $dataTables->eloquent($query)
                    ->addColumn('need_attendant', function(Registration $registration) {
                        return $registration->special_considerations['need_attendant'] ?? 'NA';
                    })
                    ->addColumn('cot_or_bed', function(Registration $registration) {
                        return $registration->special_considerations['cot_or_bed'] ?? 'NA';
                    })->rawColumns(['need_attendant', 'cot_or_bed'])->addIndexColumn()->make(true);
        }
        return view('admin.reports.common-data-report');
    }
    public function purchaseDataReport(Request $request, DataTables $datatables) {

        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('View PurchaseDataReport')){
            abort(403);
        }
        if($request->ajax()) {
            $query = RegPurchasesDetail::with('registration', 'registration.member')->where('qty', '>', 0)->where(function ($query) use ($request) {
                if (isset($request->purchase_type))
                    $query->where('type', $request->purchase_type);
            })->where(function ($query) use($request) {
                if(isset($request->zone_name)){
                    $query->whereHas('registration.member', function ($q) use ($request) {
                        $q->where('zone_name', $request->zone_name);
                    });
                }
                if(isset($request->division_name)){
                    $query->whereHas('registration.member', function ($q) use ($request) {
                        $q->where('division_name', $request->division_name);
                    });
                }
                if(isset($request->unit_name)){
                    $query->whereHas('registration.member', function ($q) use ($request) {
                        $q->where('unit_name', $request->unit_name);
                    });
                }
                if(isset($request->gender)){
                    $query->whereHas('registration.member', function ($q) use ($request) {
                        $q->where('gender', $request->gender);
                    });
                }
                $query->whereHas('registration.member', function ($q) use ($request) {
                    $q->filterByZone();
                });
            })->orderBy('id', 'asc');
            return $datatables->eloquent($query)->addIndexColumn()->make(true);
        }
        return view('admin.reports.purchase-data-report');
    }
    public function sightSeeingDetailsReport(Request $request, DataTables $dataTables) {

        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('View SightSeeingReport')){
            abort(403);
        }
        if($request->ajax()) {
            $query = Registration::with('member')->whereHas('member', function ($query) use ($request) {
                if (!empty($request->unit_name)) {
                    $query->where('unit_name', $request->unit_name);
                }
                if (!empty($request->zone_name)) {
                    $query->where('zone_name', $request->zone_name);
                }
                if (!empty($request->division_name)) {
                    $query->where('division_name', $request->division_name);
                }
                if (!empty($request->gender)) {
                    $query->where('gender', $request->gender);
                }
                $query->filterByZone();
            })->select('registrations.*')->where([['confirm_arrival', '=', 1], ['sight_seeing->required', 'yes']])
                ->orderBy('id', 'asc');
            return $dataTables->eloquent($query)
                ->addColumn('total_members', function (Registration $registration) {
                    return $registration->sight_seeing['members_count'] ?? 0;
                })
                ->rawColumns(['total_members'])
                ->addIndexColumn()->make(true);
        }
        return view('admin.reports.sight-seeing-details-report');
    }
    public function globalReport(Request $request, DataTables $dataTables) {

        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('View GlobalReport')){
            abort(403);
        }
        if($request->ajax()) {
            $zoneFilter = $request->zone_name;
            if(!empty($user->zone_name)) 
                $zoneFilter = $user->zone_name;

            $queryBy = !empty($zoneFilter) ? 'division_name' : 'zone_name';
            $globalData = array_reduce($this->globalReportData($zoneFilter), 'array_merge', []);
            $query = Member::select(DB::raw("$queryBy , count(*) as total_arkans"))->where(function($query) use($zoneFilter, $queryBy) {
                if(!empty($zoneFilter)) {
                    $query->where('zone_name', $zoneFilter);
                }
            })->groupBy($queryBy)->orderBy($queryBy, 'asc');
            return $dataTables->eloquent($query)->addColumn('zone_name', function(Member $member) use($zoneFilter) {
                return !empty($zoneFilter) ?  $member->division_name : $member->zone_name;
            })->rawColumns(['zone_name'])->with('total_registered', $globalData)->addIndexColumn()->make(true);
        }
        return view('admin.reports.global-report');
    }
    public function globalReportData($zoneFilter) {
        $data = [];
        $queryBy = !empty($zoneFilter) ? 'division_name' : 'zone_name';
        $distinctData = Member::select('zone_name','division_name')->where(function($query) use($zoneFilter) {
            if(!empty($zoneFilter)) {
                $query->where('zone_name', $zoneFilter);
            }
        })->distinct()->orderBy($queryBy, 'asc')->get();
        foreach ($distinctData as $item) {
            $filterType = !empty($zoneFilter) ? $item->division_name : $item->zone_name;
            $totalArkans = Member::where($queryBy, $filterType)->count();
            $totalAttendees = Registration::with('member')->whereHas('member', function ($query) use ($filterType, $queryBy) {
                        $query->where($queryBy, $filterType);
                    })->where('confirm_arrival', 1)->count();
            $totaNonAttendees = Registration::with('member')->whereHas('member', function ($query) use ($filterType, $queryBy) {
                        $query->where($queryBy, $filterType);
                    })->where('confirm_arrival', 0)->count();
            $totalRegistered = Registration::with('member')->whereHas('member', function ($query) use ($filterType, $queryBy) {
                        $query->where($queryBy, $filterType);
                    })->count();
            $sortedData= [
                $filterType => [
                    'registered' => $totalRegistered,
                    'total_attendees' => $totalAttendees,
                    'total_non_attendees' => $totaNonAttendees,
                    'tot_attendees_percentage' => floatval($totalArkans > 0 ? round(($totalAttendees / $totalArkans) * 100, 2) : 0),
                    'tot_registered_percentage' => floatval($totalArkans > 0 ? round(($totalRegistered / $totalArkans) * 100, 2) : 0)
                ],
                "total_non_attendees" => Registration::with('member')->whereHas('member', function($query) use($zoneFilter) {
                    if(!empty($zoneFilter)) {
                        $query->where('zone_name', $zoneFilter);
                    }
                })->where('confirm_arrival', 0)->count(),
                "total_attendees" => Registration::with('member')->whereHas('member', function($query) use($zoneFilter) {
                    if(!empty($zoneFilter)) {
                        $query->where('zone_name', $zoneFilter);
                    }
                })->where('confirm_arrival', 1)->count(),
                "total_registered" => Registration::with('member')->whereHas('member', function($query) use($zoneFilter) {
                    if(!empty($zoneFilter)) {
                        $query->where('zone_name', $zoneFilter);
                    }
                })->count(),
            ];
            array_push($data, $sortedData);
        }
        return $data;
    }
}