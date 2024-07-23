<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\RegFamilyDetail;
use Yajra\DataTables\DataTables;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Exception\MessagingException;

class ReportsController extends Controller
{

    public function tourReport(Request $request, DataTables $dataTables){

        if (auth()->user()->id != 1 && !auth()->user()->hasPermissionTo('View TourReport')){
            abort(403);
        }

        if ($request->ajax()) {

            $query = Registration::with('member')->whereHas('member', function ($query) use ($request) {
                if (isset($request->zone_name)) {
                    $query->where('zone_name', $request->zone_name);
                }
                if (isset($request->gender)) {
                    $query->where('gender', $request->gender);
                }
                if (isset($request->unit_name)) {
                    $query->where('unit_name', $request->unit_name);
                }
                if (isset($request->division_name)) {
                    $query->where('division_name', $request->division_name);
                }
            })->select('registrations.*')->orderBy('id', 'asc');

            return $dataTables->eloquent($query)
                ->editColumn('members_count', function (Registration $registration) {
                    return $registration->sight_seeing ? $registration->sight_seeing['members_count'] : '<span class="badge badge-danger">0</span>';
                })
                ->rawColumns(['members_count'])
                    ->addIndexColumn()->make(true);
        }

        return view('admin.reports.tour-report');
    }

    public function healthReport(Request $request, DataTables $dataTables){
        if (auth()->user()->id != 1 && !auth()->user()->hasPermissionTo('View HealthReport')){
            abort(403);
        }

        if ($request->ajax()) {
            $query = Registration::with('member')->whereHas('member', function ($query) use ($request) {
                if (isset($request->zone_name)) {
                    $query->where('zone_name', $request->zone_name);
                }
                if (isset($request->gender)) {
                    $query->where('gender', $request->gender);
                }
            })->select('registrations.*')->where(function ($query) use ($request) {
                if (isset($request->health_concern)) {
                    $query->where('health_concern', $request->health_concern);
                }
            })->orderBy('id', 'asc');

            return $dataTables->eloquent($query)
                ->editColumn('health_concern', function (Registration $registration) {
                    return $registration->health_concern ? $registration->health_concern : '<span class="badge badge-danger">NA</span>';
                })
                ->addColumn('zone_name', function (Registration $registration) {
                    return $registration->member->zone_name;
                })
                ->addColumn('gender', function (Registration $registration) {
                    return $registration->member->gender;
                })
                ->addColumn('action', function (Registration $registration) {
                    return '<a href="' . route('registrations.show', $registration->id) . '" class="badge badge-primary" title="View"><i class="fas fa-arrow-right"></i></a>';
                })
                ->rawColumns(['health_concern', 'action']) // Ensure these columns are rendered as HTML
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.reports.health-report');
    }

    public function arrivalReport(Request $request, DataTables $dataTables) {


        if (auth()->user()->id != 1 && !auth()->user()->hasPermissionTo('View TravelReport')){
            abort(403);
        }

        if($request->ajax()) {
            $query = Registration::with('member')->whereHas('member', function ($query) use ($request) {
                if (isset($request->unit_name)) {
                    $query->where('unit_name', $request->unit_name);
                }
                if (isset($request->zone_name)) {
                    $query->where('zone_name', $request->zone_name);
                }
                if (isset($request->division_name)) {
                    $query->where('division_name', $request->division_name);
                }
            })->select('registrations.*')->where([['confirm_arrival', '=', 1], ['arrival_details', '!=', '']])->orderBy('id', 'asc');
            return $dataTables->eloquent($query)
                ->addColumn('travel_mode', function (Registration $registration) {
                    return $registration->arrival_details['mode'] ?? 'NA';
                })
                ->addColumn('date_time', function (Registration $registration) {
                    return $registration->arrival_details['datetime'] ?
                        '<span class="badge badge-success">' . date('Y-m-d', strtotime($registration->arrival_details['datetime'])) . '</span>' : 'NA';
                    })
                ->addColumn('start_point', function (Registration $registration) {
                    return $registration->arrival_details['start_point'] ?
                        $registration->arrival_details['start_point'] : 'NA';
                    })
                ->addColumn('end_point', function (Registration $registration) {
                    return $registration->arrival_details['end_point'] ?
                        $registration->arrival_details['end_point'] : 'NA';
                    })
                ->addColumn('mode_identifier', function (Registration $registration) {
                    return $registration->arrival_details['mode_identifier'] ?
                        $registration->arrival_details['mode_identifier'] : 'NA';
                    })
                ->rawColumns(['travel_mode', 'date_time', 'start_point', 'end_point', 'mode_identifier'])
                    ->addIndexColumn()->make(true);
        }
        return view('admin.reports.arrival-report');
    }

     public function departureReport(Request $request, DataTables $dataTables) {

        if (auth()->user()->id != 1 && !auth()->user()->hasPermissionTo('View TravelReport')){
            abort(403);
        }

        if($request->ajax()) {
            $query = Registration::with('member')->whereHas('member', function ($query) use ($request) {
                if (isset($request->unit_name)) {
                    $query->where('unit_name', $request->unit_name);
                }
                if (isset($request->zone_name)) {
                    $query->where('zone_name', $request->zone_name);
                }
                if (isset($request->division_name)) {
                    $query->where('division_name', $request->division_name);
                }
            })->select('registrations.*')->where([['confirm_arrival', '=', 1], ['departure_details', '!=', '']])->orderBy('id', 'asc');
            return $dataTables->eloquent($query)
                ->addColumn('travel_mode', function (Registration $registration) {
                    return $registration->departure_details['mode'] ?? 'NA';
                })
                ->addColumn('date_time', function (Registration $registration) {
                    return $registration->departure_details['datetime'] ?
                        '<span class="badge badge-success">' . date('Y-m-d', strtotime($registration->departure_details['datetime'])) . '</span>' : 'NA';
                    })
                ->addColumn('start_point', function (Registration $registration) {
                    return $registration->departure_details['start_point'] ?
                        $registration->departure_details['start_point'] : 'NA';
                    })
                ->addColumn('end_point', function (Registration $registration) {
                    return $registration->departure_details['end_point'] ?
                        $registration->departure_details['end_point'] : 'NA';
                    })
                ->addColumn('mode_identifier', function (Registration $registration) {
                    return $registration->departure_details['mode_identifier'] ?
                        $registration->departure_details['mode_identifier'] : 'NA';
                    })
                ->rawColumns(['travel_mode', 'date_time', 'start_point', 'end_point', 'mode_identifier'])
                    ->addIndexColumn()->make(true);
        }
        return view('admin.reports.departure-report');
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