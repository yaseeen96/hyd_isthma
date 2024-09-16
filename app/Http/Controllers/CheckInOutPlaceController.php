<?php

namespace App\Http\Controllers;

use App\Models\CheckInOutPlace;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CheckInOutPlaceController extends Controller
{
    protected $members_types = [
        'arkan' => 'Arkan',
        'mehram' => 'Mehram',
        'vendor' => 'Vendor',
        'govt' => 'Govt',
        'volunteer' => 'Volunteer',
    ];
    protected $genders = [
        'male' => 'Male',
        'female' => 'Female'
    ];
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, DataTables $dataTables)
    {
        $user = User::find(auth()->user()->id);
        if ( $user->id != 1 && ! $user->hasPermissionTo('View CheckInOutPlaces')){
            abort(403);
        }
        if($request->ajax()) {
            $data = CheckInOutPlace::all();
            return $dataTables->of($data)
                ->addColumn('action', function($row) use($user){
                    $btn  = $user->id == 1 || $user->hasPermissionTo('Edit CheckInOutPlaces') ? '<a href="'.route('checkInOutPlaces.edit', $row->id).'" class="btn-purple btn mr-1">Edit</a>' : '';
                    $btn .= $user->id == 1 || $user->hasPermissionTo('Delete CheckInOutPlaces') ?
                            '<span data-href="'.route('checkInOutPlaces.destroy', $row->id).'" class="btn-purple place-delete btn"><i class="fas fa-trash"></i></span>'
                            : "";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.checkinoutplaces.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::find(auth()->user()->id);
        if ( $user->id != 1 && ! $user->hasPermissionTo('Create CheckInOutPlaces')){
            abort(403);
        }
        return view('admin.checkinoutplaces.form')->with(
            [
                'place' => new CheckInOutPlace(),
                'members_types' => $this->members_types,
                'genders' => $this->genders
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'place_name' => 'required',
        ]);
        CheckInOutPlace::create($request->all());
        return redirect()->route('checkInOutPlaces.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(CheckInOutPlace $checkInOutPlace)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CheckInOutPlace $checkInOutPlace)
    {
        $user = User::find(auth()->user()->id);
        if ( $user->id != 1 && ! $user->hasPermissionTo('Edit CheckInOutPlaces')){
            abort(403);
        }
        return view('admin.checkinoutplaces.form')->with(
            [
                'place' => $checkInOutPlace,
                'members_types' => $this->members_types,
                'genders' => $this->genders
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CheckInOutPlace $checkInOutPlace)
    {
        $request->validate([
            'place_name' => 'required',
        ]);
        $checkInOutPlace->update($request->all());
        return redirect()->route('checkInOutPlaces.edit', $checkInOutPlace->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CheckInOutPlace $checkInOutPlace)
    {
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('Delete CheckInOutPlaces')){
            abort(403);
        }
        // TODO: Implement delete check once the check in out entires table got created and linked with this table
        $user = CheckInOutPlace::where('id', $checkInOutPlace->id)->first();
        $user->delete();
        return response()->json([
            'message' => 'Deleted Successfully',
            'status' => 200
        ], 200);
    }
}