<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\RegFamilyDetail;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, DataTables $dataTables)
    {
        if ( !(auth()->user()->hasPermissionTo('View Registrations')) && auth()->user()->id != 1){
                abort(403);
        }

        if ($request->ajax()) {
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
            })->select('registrations.*')->where(function ($query) use ($request) {
                if (isset($request->confirm_arrival)) {
                    $query->where('confirm_arrival', $request->confirm_arrival);
                }
            })->orderBy('id', 'asc');
            return $dataTables->eloquent($query)
                ->editColumn('confirm_arrival', function (Registration $registration) {
                    return $registration->confirm_arrival ? '<span class="badge badge-success">Confirmed</span>' : '<span class="badge badge-danger">NA</span>';
                })
                ->editColumn('ameer_permission_taken', function (Registration $registration) {
                    return $registration->ameer_permission_taken ? '<span class="badge badge-success">Yes</span>' : ($registration->ameer_permission_taken === 0 ? '<span class="badge badge-danger">NO</span>' : '-');
                })
                ->addColumn('action', function (Registration $registration) {
                    return   '<a href="' . route('registrations.show', $registration->id) . '" class="badge badge-primary" title="View"><i class="fas fa-eye" ></i></a>';
                })->rawColumns(['confirm_arrival', 'ameer_permission_taken', 'action'])->addIndexColumn()->make(true);
        }

        return view('admin.registrations.list');
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
    public function show(string $id)
    {
        // if ( auth()->user()->id != 1 && !auth()->user()->hasPermissionTo('View Registrations')){
        //     abort(403);
        // }

        $member = Registration::with('member')->where('id', $id)->get()->first();
        $registration = Registration::with(['familyDetails', 'purchaseDetails'])->find($id);
        $purchaseDetails =   $registration->purchaseDetails;

        $mehramDetails = $registration->familyDetails->where('type', 'mehram');
        $childrenDetails = $registration->familyDetails->where('type', 'children');

        $data['member'] = $member;
        $data['mehramDetails'] = $mehramDetails;
        $data['childrenDetails'] = $childrenDetails;
        $data['purchaseDetails'] = $purchaseDetails;
        return view('admin.registrations.show', $data);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}