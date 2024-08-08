<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\RegFamilyDetail;
use App\Models\RegPurchasesDetail;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Exception\MessagingException;

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
            $query = RegFamilyDetail::with('registration')->where(function ($query) use ($request) {
                if (isset($request->gender))
                    $query->where('gender', $request->gender);
                if (isset($request->age_group))
                    $query->where('type', $request->age_group);
                if (isset($request->interested_in_volunteering)) {
                    $request->interested_in_volunteering = $request->interested_in_volunteering == 'null' ? null : $request->interested_in_volunteering;
                    $query->where('interested_in_volunteering', $request->interested_in_volunteering);
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
            })->orderBy('id', 'asc');
            return $datatables->eloquent($query)
                    ->addColumn('name_of_rukun', function (RegFamilyDetail $familyDetail) {
                        return $familyDetail->registration->member->name;
                    })
                    ->addColumn('rukun_id', function (RegFamilyDetail $familyDetail) {
                        return $familyDetail->registration->member->user_number;
                    })
                    ->addColumn('phone', function(RegFamilyDetail $familyDetail) {
                        return $familyDetail->registration->member->phone;
                    })
                    ->addColumn('unit_name', function(RegFamilyDetail $familyDetail) {
                        return $familyDetail->registration->member->unit_name;
                    })
                    ->addColumn('division_name', function(RegFamilyDetail $familyDetail) {
                        return $familyDetail->registration->member->division_name;
                    })
                    ->addColumn('zone_name', function(RegFamilyDetail $familyDetail) {
                        return $familyDetail->registration->member->zone_name;
                    })
                    ->editColumn('interested_in_volunteering', function(RegFamilyDetail $familyDetail) {
                    return ($familyDetail->interested_in_volunteering == null ? '' : ($familyDetail->interested_in_volunteering == 'yes' ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-danger">No</span>'));
                    })
                    ->rawColumns(['name_of_rukun', 'rukun_id', 'phone', 'unit_name', 'division_name', 'zone_name', 'interested_in_volunteering'])->addIndexColumn()->make(true);

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
            });
            return $datatable->eloquent($query)
                ->addColumn('name', function (Registration $registration) {
                    return $registration->member->name;
                })
                ->addColumn('user_number', function (Registration $registration) {
                    return $registration->member->user_number;
                })
                ->addColumn('phone', function (Registration $registration) {
                    return $registration->member->phone;
                })
                ->addColumn('unit_name', function (Registration $registration) {
                    return $registration->member->unit_name;
                })
                ->addColumn('division_name', function (Registration $registration) {
                    return $registration->member->division_name;
                })
                ->addColumn('zone_name', function (Registration $registration) {
                    return $registration->member->zone_name;
                })
                ->addColumn('gender', function (Registration $registration) {
                    return $registration->member->gender;
                })
                ->addColumn('member_fees', function (Registration $registration) {
                        $memberFee = $registration->member_fees ?? 0;
                        $familyFee = RegFamilyDetail::where('registration_id', $registration->id)->sum('fees');
                        return $memberFee + $familyFee;
                    })
                ->rawColumns(['name', 'user_number', 'phone', 'unit_name', 'division_name', 'zone_name', 'gender', 'total_fees'])
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
            $query = RegPurchasesDetail::with('registration')->where('qty', '>', 0)->where(function ($query) use ($request) {
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
            })->orderBy('id', 'asc');
            return $datatables->eloquent($query)
                    ->addColumn('name_of_rukun', function (RegPurchasesDetail $regPurchaseDtls) {
                        return $regPurchaseDtls->registration->member->name;
                    })
                    ->addColumn('rukun_id', function (RegPurchasesDetail $regPurchaseDtls) {
                        return $regPurchaseDtls->registration->member->user_number;
                    })
                    ->addColumn('phone', function(RegPurchasesDetail $regPurchaseDtls) {
                        return $regPurchaseDtls->registration->member->phone;
                    })
                    ->addColumn('unit_name', function(RegPurchasesDetail $regPurchaseDtls) {
                        return $regPurchaseDtls->registration->member->unit_name;
                    })
                    ->addColumn('division_name', function(RegPurchasesDetail $regPurchaseDtls) {
                        return $regPurchaseDtls->registration->member->division_name;
                    })
                    ->addColumn('zone_name', function(RegPurchasesDetail $regPurchaseDtls) {
                        return $regPurchaseDtls->registration->member->zone_name;
                    })
                    ->editColumn('gender', function(RegPurchasesDetail $regPurchaseDtls) {
                        return $regPurchaseDtls->registration->member->gender;
                    })
                    ->rawColumns(['name_of_rukun', 'rukun_id', 'phone', 'unit_name', 'division_name', 'zone_name', 'gender'])->addIndexColumn()->make(true);
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
    public function testing(Request $request) {
        $token = $request->get('token');
        if (isset($token)){
           $messaging = app('firebase.messaging');
        // $deviceToken = 'duCncov_Qk6q4sTWflgLhe:APA91bFcDqy-iRBaIHuyNkZZto8AcnGRvgkLFKm3RDUpNqUl2NuvHseGy4s-bnkznbARlk6n32vMZIcC3LzuoHvQAvEj9ju-MxRGnfMKRihcuz8f9dTAB67Aa8KUfiuyamK-LGkuThLb';
            $deviceToken = $token;
            $message = CloudMessage::withTarget('token', $deviceToken)
                ->withNotification(Notification::create('Jih Push Notification', 'Testing Jih Push Notification'));
            try {
                $res = $messaging->send($message);
                dd($res);
            } catch (MessagingException $e) {
                echo $e->getMessage();
                print_r($e->errors());
            }
        }
    }
}