<?php

namespace App\Http\Controllers;

use App\Models\checkInOutEntires;
use App\Models\User;
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
            $query = checkInOutEntires::with('checkInOutPlace', 'user')->orderBy('id', 'desc');
            return $datatables->eloquent($query)
                ->editColumn('datetime', function(checkInOutEntires $checkInOutEntires){
                    return $checkInOutEntires->datetime != null ? date('Y-m-d h:s a', strtotime($checkInOutEntires->datetime)) : 'NA';
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
        return view('admin.checkinoutentries.list');
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