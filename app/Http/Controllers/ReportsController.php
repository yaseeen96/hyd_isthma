<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\RegFamilyDetail;
use Yajra\DataTables\DataTables;

class ReportsController extends Controller
{
    // Itenary Report
    public function travelReport() {
        return view('admin.reports.travel-report');  
    }

    public function healthReport(Request $request, DataTables $dataTables){
        if (auth()->user()->id != 1) {
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
    
}