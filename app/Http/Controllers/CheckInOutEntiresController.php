<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelperFunctions;
use App\Models\checkInOutEntires;
use App\Models\CheckInOutPlace;
use App\Models\QrBatchRegistration;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CheckInOutEntiresController extends Controller
{
    /**
     * Total count by place
     */
    public function totalCountByPlace(Request $request, DataTables $datatables) {
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('View ScannEntires')){
            abort(403);
        }
        if($request->ajax()) {
            $query = checkInOutEntires::select(
                'place_id',
                DB::raw('COUNT(*) as total_count')  // Count of rows in each group
            )
                ->where(function ($query) use ($request) {
                    if (!empty($request->unit_name)) {
                        $query->where('unit_name', $request->unit_name);
                    }
                    if (!empty($request->zone_name)) {
                        $query->where('zone_name', $request->zone_name);
                    }
                    if (!empty($request->division_name)) {
                        $query->where('division_name', $request->division_name);
                    }
                    if (!empty($request->date)) {
                        $query->whereDate('date', $request->date);
                    }
                    if (!empty($request->from_time) && empty($request->to_time)) {
                        $query->whereTime('time', date('H:i:s', strtotime($request->from_time)));
                    }
                    if (empty($request->from_time) && !empty($request->to_time)) {
                        $query->whereTime('time', date('H:i:s', strtotime($request->to_time)));
                    }
                    if (!empty($request->from_time) && !empty($request->to_time)) {
                        $query->whereTime('time', '>=', date('H:i:s', strtotime($request->from_time)))
                            ->whereTime('time', '<=', date('H:i:s', strtotime($request->to_time)));
                    }
                    if(!empty($request->batch_type)) {
                        $query->where('batch_type', $request->batch_type);
                    }
                    if(!empty($request->category)) {
                        $query->where('category', $request->category);
                    }
                    if (!empty($request->mode)) {
                        $query->where('mode', $request->mode);
                    }
                })
                ->groupBy('place_id')
                ->orderBy('total_count', 'asc');  // Ordering by place_id or any column you prefer
            return $datatables->eloquent($query)
                ->editColumn('total_count', function ($query) {
                    return AppHelperFunctions::getGreenBadge($query->total_count);
                })
                ->addColumn('place_name', function($query){
                    return isset($query->checkInOutPlace) ? $query->checkInOutPlace->place_name : 'NA';
                })
                ->addIndexColumn()
                ->rawColumns(['total_count', 'place_name'])
                ->make(true);
        }
        $checkInOutPlaces = CheckInOutPlace::query()->get();
        $batchTypes = QrBatchRegistration::distinct('batch_type')->pluck('batch_type')->toArray();
        array_push($batchTypes, 'rukun');
        $qrOperators = User::role(4)->get();
        return view('admin.checkinoutentries.total-count-by-place', compact('checkInOutPlaces', 'batchTypes', 'qrOperators'));
    }
    /**
     * Percentage Report
    */
    public function positionReport(Request $request, DataTables $datatables)
    {
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('View ScannEntires')){
            abort(403);
        }
        if($request->ajax()) {
            $query = checkInOutEntires::with('checkInOutPlace', 'user')
                ->where(function ($query) use ($request) {
                    if (!empty($request->place_id)) {
                        $query->where('place_id', $request->place_id);
                    }
                    if (!empty($request->mode)) {
                        $query->where('mode', $request->mode);
                    }
                    if (!empty($request->unit_name)) {
                         $query->where('unit_name', $request->unit_name);
                    }
                    if (!empty($request->zone_name)) {
                        $query->where('zone_name', $request->zone_name);
                    }
                    if (!empty($request->division_name)) {
                        $query->where('division_name', $request->division_name);
                    }
                    if(!empty($request->date)) {
                        $query->whereDate('date', $request->date);
                    }
                    if(!empty($request->from_time) && empty($request->to_time)) {
                        $query->whereTime('time', date('H:i:s', strtotime($request->from_time)));
                    }
                    if(empty($request->from_time) && !empty($request->to_time)) {
                        $query->whereTime('time', date('H:i:s', strtotime($request->to_time)));
                    }
                    if(!empty($request->from_time) && !empty($request->to_time)) {
                        $query->whereTime('time', '>=', date('H:i:s', strtotime($request->from_time)))->whereTime('time', '<=', date('H:i:s', strtotime($request->to_time)));
                    }
                })
                ->whereIn('id', function ($subquery) {
                    $subquery->select(DB::raw('MAX(id)'))
                        ->from('check_in_out_entires')
                        ->groupBy('batch_id')
                        ->orderBy('created_at', 'desc');
                })->orderBy('created_at', 'desc');
            return $datatables->eloquent($query)
                ->editColumn('datetime', function(checkInOutEntires $checkInOutEntires){
                    $datetime = $checkInOutEntires->date != null ? AppHelperFunctions::getGreenBadge(date('Y-m-d', strtotime($checkInOutEntires->date))) : 'NA';
                    $datetime .= ' ';
                    $datetime .= $checkInOutEntires->time != null ? AppHelperFunctions::getGreenBadge(Carbon::parse($checkInOutEntires->time)->format('h:i A')) : 'NA';
                    return $datetime;
                })
                ->addColumn('place', function(checkInOutEntires $checkInOutEntires){
                    return isset($checkInOutEntires->checkInOutPlace) ? $checkInOutEntires->checkInOutPlace->place_name : 'NA';
                })
                ->addColumn('user', function(checkInOutEntires $checkInOutEntires){
                    return isset($checkInOutEntires->user) ? $checkInOutEntires->user->name : 'NA';
                })
                ->addIndexColumn()
                ->rawColumns(['place', 'user', 'datetime'])
                ->make(true);
        }
        $checkInOutPlaces = CheckInOutPlace::query()->get();
        $batchTypes = QrBatchRegistration::distinct('batch_type')->pluck('batch_type')->toArray();
        array_push($batchTypes, 'rukun');
        $qrOperators = User::role(4)->get();
        return view('admin.checkinoutentries.position-report', compact('checkInOutPlaces', 'batchTypes', 'qrOperators'));
    }

    public function totalCheckInOutReport(Request $request, DataTables $datatables)
    {
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('View ScannEntires')){
            abort(403);
        }
        if($request->ajax()) {
            $query = checkInOutEntires::select(
                'batch_id',
                'batch_type',
                'gender',
                'name',
                'zone_name',
                'division_name',
                'unit_name',
                'phone_number',
                'category',
                DB::raw('COUNT(*) as total_count')  // Count of rows in each group
            )
                ->where(function ($query) use ($request) {
                    if (!empty($request->place_id)) {
                        $query->where('place_id', $request->place_id);
                    }
                    if (!empty($request->mode)) {
                        $query->where('mode', $request->mode);
                    }
                    if (!empty($request->unit_name)) {
                        $query->where('unit_name', $request->unit_name);
                    }
                    if (!empty($request->zone_name)) {
                        $query->where('zone_name', $request->zone_name);
                    }
                    if (!empty($request->division_name)) {
                        $query->where('division_name', $request->division_name);
                    }
                    if (!empty($request->date)) {
                        $query->whereDate('date', $request->date);
                    }
                    if(!empty($request->batch_type)) {
                        $query->where('batch_type', $request->batch_type);
                    }
                    if(!empty($request->category)) {
                        $query->where('category', $request->category);
                    }
                    if (!empty($request->from_time) && empty($request->to_time)) {
                        $query->whereTime('time', date('H:i:s', strtotime($request->from_time)));
                    }
                    if (empty($request->from_time) && !empty($request->to_time)) {
                        $query->whereTime('time', date('H:i:s', strtotime($request->to_time)));
                    }
                    if (!empty($request->from_time) && !empty($request->to_time)) {
                        $query->whereTime('time', '>=', date('H:i:s', strtotime($request->from_time)))
                            ->whereTime('time', '<=', date('H:i:s', strtotime($request->to_time)));
                    }
                })
                ->groupBy('batch_id', 'batch_type', 'gender', 'name', 'zone_name', 'division_name', 'unit_name', 'phone_number', 'category')
                ->orderBy('batch_id', 'desc');  // Ordering by batch_id or any column you prefer
            return $datatables->eloquent($query)
                ->editColumn('total_count', function ($query) {
                    return AppHelperFunctions::getGreenBadge($query->total_count);
                })
                ->addIndexColumn()
                ->rawColumns(['total_count'])
                ->make(true);
        }
        $checkInOutPlaces = CheckInOutPlace::query()->get();
        $batchTypes = QrBatchRegistration::distinct('batch_type')->pluck('batch_type')->toArray();
        array_push($batchTypes, 'rukun');
        $qrOperators = User::role(4)->get();
        return view('admin.checkinoutentries.total-check-in-out-report', compact('checkInOutPlaces', 'batchTypes', 'qrOperators'));
    }

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request, DataTables $datatables)
    {
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('View ScannEntires')){
            abort(403);
        }
        if($request->ajax()) {
            $query = checkInOutEntires::with('checkInOutPlace', 'user')->where(function($query) use($request){
                if (!empty($request->unit_name)) {
                    $query->where('unit_name', $request->unit_name);
                }
                if (!empty($request->zone_name)) {
                    $query->where('zone_name', $request->zone_name);
                }
                if (!empty($request->division_name)) {
                    $query->where('division_name', $request->division_name);
                }
                if(!empty($request->place_id)) {
                    $query->where('place_id', $request->place_id);
                }
                if(!empty($request->mode)) {
                    $query->where('mode', $request->mode);
                }
                if(!empty($request->batch_type)) {
                    $query->where('batch_type', $request->batch_type);
                }
                if(!empty($request->category)) {
                    $query->where('category', $request->category);
                }
                if(!empty($request->date)) {
                    $query->whereDate('date', $request->date);
                }
                if(!empty($request->from_time) && empty($request->to_time)) {
                    $query->whereTime('time', date('H:i:s', strtotime($request->from_time)));
                }
                if(empty($request->from_time) && !empty($request->to_time)) {
                    $query->whereTime('time', date('H:i:s', strtotime($request->to_time)));
                }
                if(!empty($request->from_time) && !empty($request->to_time)) {
                    $query->whereTime('time', '>=', date('H:i:s', strtotime($request->from_time)))->whereTime('time', '<=', date('H:i:s', strtotime($request->to_time)));
                }
                if(!empty($request->qr_operator)) {
                    $query->where('operator_id', $request->qr_operator);
                }
                if(!empty($request->batch_id) && strlen($request->batch_id) > 3) {
                    $query->where('batch_id', $request->batch_id);
                }
            })->orderBy('id', 'desc');
            return $datatables->eloquent($query)
                ->editColumn('datetime', function(checkInOutEntires $checkInOutEntires){
                    $datetime = $checkInOutEntires->date != null ? AppHelperFunctions::getGreenBadge(date('Y-m-d', strtotime($checkInOutEntires->date))) : 'NA';
                    $datetime .= ' ';
                    $datetime .= $checkInOutEntires->time != null ? AppHelperFunctions::getGreenBadge(Carbon::parse($checkInOutEntires->time)->format('h:i A')) : 'NA';
                    return $datetime;
                })
                ->addColumn('place', function(checkInOutEntires $checkInOutEntires){
                    return isset($checkInOutEntires->checkInOutPlace) ? $checkInOutEntires->checkInOutPlace->place_name : 'NA';
                })
                ->addColumn('user', function(checkInOutEntires $checkInOutEntires){
                    return isset($checkInOutEntires->user) ? $checkInOutEntires->user->name : 'NA';
                })

                ->addIndexColumn()
                ->rawColumns(['place', 'user', 'datetime'])
                ->make(true);
        }
        $checkInOutPlaces = CheckInOutPlace::query()->get();
        $batchTypes = QrBatchRegistration::distinct('batch_type')->pluck('batch_type')->toArray();
        array_push($batchTypes, 'Rukn');
        $qrOperators = User::role(4)->get();
        return view('admin.checkinoutentries.list', compact('checkInOutPlaces', 'batchTypes', 'qrOperators'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(checkInOutEntires $checkInOutEntires)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(checkInOutEntires $checkInOutEntires)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, checkInOutEntires $checkInOutEntires)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(checkInOutEntires $checkInOutEntires)
    {
        //
    }
}
