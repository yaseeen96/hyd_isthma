<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelperFunctions;
use App\Models\Feedback;
use App\Models\Program;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, DataTables $datatable)
    {
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('View Feeback')){
            abort(403);
        }
        $programs = Program::all();
        if($request->ajax()) {
            $query = Feedback::with('member')->where(function($query){
                if (request()->has('feedback_type') && !empty(request()->feedback_type)) {
                    $query->where('feedback_type', request()->feedback_type);
                }
                if (request()->has('program_id') && !empty(request()->program_id)) {
                    $query->where('program_id', request()->program_id);
                }
                if (request()->has('member_id') && !empty(request()->member_id)) {
                    $query->where('member_id', request()->member_id);
                }
                if (request()->has('datetime') && !empty(request()->datetime)) {
                    $query->where('datetime', 'like', '%' . request()->datetime . '%');
                }
            })->whereHas('member', function ($query) use ($request) {
                if (isset($request->unit_name)) {
                    $query->where('unit_name', $request->unit_name);
                }
                if (isset($request->zone_name)) {
                    $query->where('zone_name', $request->zone_name);
                }
                if (isset($request->division_name)) {
                    $query->where('division_name', $request->division_name);
                }
            })->orderBy('id', 'desc');
            return $datatable->eloquent($query)
                ->addColumn('datatime', function (Feedback $feedback) {
                    return !empty($feedback->datetime) ? AppHelperFunctions::getGreenBadge(date('d-m-Y H:i:s', strtotime($feedback->datetime))) : '';
                })
                ->addColumn('feedback_type', function (Feedback $feedback) {
                    return $feedback->feedback_type == 'event' ? AppHelperFunctions::getBadge('Event', 'primary') : AppHelperFunctions::getBadge('Program', 'info');
                })
                ->addColumn('program_name', function(Feedback $feedback) {
                    return $feedback->program_id ? $feedback->program->topic : AppHelperFunctions::getBadge('NA', 'secondary');
                })
                ->rawColumns(['datatime', 'feedback_type', 'program_name'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.feedback.list', compact('programs'));
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