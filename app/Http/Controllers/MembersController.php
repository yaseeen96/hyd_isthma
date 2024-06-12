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
            $query = Member::select('members.*')->orderBy('id', 'asc');

            return $dataTables->eloquent($query)->make(true);
        }

        return view('admin.members.list');
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
        //
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