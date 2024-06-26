<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MembersController extends Controller
{
    public function index(Request $request, DataTables $dataTables)
    {
        if (auth()->user()->id != 1)
            abort(403);

        if ($request->ajax()) {
            $query = Member::select('members.*')->orderBy('name', 'asc');

            return $dataTables->eloquent($query)
                ->editColumn('dob', function (Member $member) {
                    return date('d-m-Y', strtotime($member->dob));
                })->addColumn('action', function (Member $member) {
                    return '<a href="' . route('members.edit', $member->id) . '" class="btn btn-sm btn-purple btn-clean btn-icon" title="Edit"><i class="fas fa-edit" ></i></a>';
                })->rawColumns(['dob', 'action'])->addIndexColumn()->make(true);
        }

        return view('admin.members.list');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.members.form')->with([
            'member' => new Member()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'zone_name' => 'required',
            'unit_name' => 'required',
            'division_name' => 'required'
        ]);
        
        $member = new Member();
        $member::create($request->all());
        return redirect()->route('members.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        $member->dob = date('d-m-Y', strtotime($member->dob));
        // $member = Member::find($id);
        return view('admin.members.form')->with([
            'member' => $member
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        $this->validate($request, [
            'name' => 'required',
            'zone_name' => 'required',
            'unit_name' => 'required',
            'division_name' => 'required',
            'phone' =>  'required'
        ]); 
        $updateData = [
            "name" => $request->name,
            "email" => $request->email,
            "zone_name" => $request->zone_name,
            "unit_name" => $request->unit_name,
            "division_name" => $request->division_name,
            "status" => $request->status === 'on' ? 'Active' : 'InActive',
            "dob" => date('Y-m-d', strtotime($request->dob)),
            "user_number" => $request->user_number,
            "gender" => $request->gender,
            "phone" => $request->phone,
        ];

        $member->update($updateData);
        return redirect()->route('members.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}