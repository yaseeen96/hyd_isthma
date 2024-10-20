<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelperFunctions;
use App\Models\checkInOutEntires;
use App\Models\CheckInOutPlace;
use App\Models\QrBatchRegistration;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CheckInOutEntiresController extends Controller
{
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
            })->orderBy('id', 'desc');
            return $datatables->eloquent($query)
                ->editColumn('datetime', function(checkInOutEntires $checkInOutEntires){
                    $datetime = $checkInOutEntires->date != null ? AppHelperFunctions::getGreenBadge(date('Y-m-d', strtotime($checkInOutEntires->date))) : 'NA';
                    $datetime .= $checkInOutEntires->time != null ? AppHelperFunctions::getGreenBadge(Carbon::parse($checkInOutEntires->time)->format('H:i A')) : 'NA';
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