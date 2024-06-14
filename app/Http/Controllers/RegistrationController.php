<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, DataTables $dataTables)
    {
        if (auth()->user()->id != 1)
            abort(403);

        if ($request->ajax()) {
            $query = Registration::with('member')->select('registrations.*')->orderBy('id', 'asc');
            return $dataTables->eloquent($query)
                ->editColumn('confirm_arrival', function (Registration $registration) {
                    return $registration->confirm_arrival ? '<span class="badge badge-success">Confirmed</span>' : '<span class="badge badge-danger">NA</span>';
                })
                ->addColumn('action', function (Registration $registration) {
                    return '<a href="' . route('registrations.show', $registration->id) . '" class="badge badge-primary" title="View"><i class="fas fa-eye" ></i></a>';
                })->rawColumns(['confirm_arrival', 'action'])->make(true);
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
        $member = Registration::with('member')->where('id', $id)->get()->first();
        return view('admin.registrations.show', compact('member'));
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