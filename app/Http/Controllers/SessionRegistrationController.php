<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelperFunctions;
use App\Models\Program;
use App\Models\ProgramSpeaker;
use App\Models\SessionRegistration;
use App\Models\SessionTheme;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Response;

class SessionRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, DataTables $dataTables)
    {
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && $user->hasPermissionTo('View Enrollments'))
            abort(403);
        if($request->ajax()) {
            $query = SessionRegistration::with('sessionTheme', 'member')
            ->whereHas('sessionTheme', function($query) use($request) {
                if(!empty($request->session_theme))
                    $query->where('id', $request->session_theme);
                if(!empty($request->theme_type))
                    $query->where('theme_type', $request->theme_type);
                if(!empty($request->session_convener)) {
                    $query->where('convener', $request->session_convener);
                }
            })->orderBy('id', 'desc');
            return $dataTables->eloquent($query)
                ->addColumn('session_date_time', function (SessionRegistration $sessionRegistration) {
                    return AppHelperFunctions::getGreenBadge(date('d-m-Y', strtotime($sessionRegistration->sessionTheme->date))). ' ' .AppHelperFunctions::getGreenBadge(date('h:i A', strtotime($sessionRegistration->sessionTheme->from_time))). ' - ' .AppHelperFunctions::getGreenBadge(date('h:i A', strtotime($sessionRegistration->sessionTheme->to_time)));
                })
                ->addColumn('action', function (SessionRegistration $sessionRegistration) use ($user) {
                    return $link = ($user->id == 1 || $user->hasPermissionTo('Delete Enrollments')) ?
                        '<span data-href="'.route('sessionRegistration.destroy', $sessionRegistration->id).'" class="btn-purple sessionRegistration-delete btn"><i class="fas fa-trash"></i></span>'
                            : "";
                })
                ->addIndexColumn()
                ->rawColumns([ 'session_date_time', 'action'])
                ->make(true);
        }
        return view('admin.sessionregistration.list')->with(
            [
                'programs' => Program::where('status', '1')->get(),
                'speakers' => ProgramSpeaker::all(),
                'sessionThemes' => SessionTheme::all()
                ]
        );
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
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('Delete Enrollments')) {
            return response()->json([
                'message' => "You don't have permission to delete Enrollment",
            ], Response::HTTP_BAD_REQUEST);
        }
        SessionRegistration::find($id)->delete();
        return response()->json([
                'message' => 'Program enrollment deleted successfully',
            ], Response::HTTP_OK);
    }
}
