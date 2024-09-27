<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelperFunctions;
use App\Models\Program;
use App\Models\ProgramSpeaker;
use App\Models\SessionTheme;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Response;

class ProgramsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, DataTables $datatable)
    {
         $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('View Programs')){
            abort(403);
        }
        if($request->ajax()) {
            $query = Program::with('programSpeaker', 'sessionTheme');
            return $datatable->eloquent($query)
                ->editColumn('date', function (Program $program) {
                    return AppHelperFunctions::getGreenBadge(date('d-m-Y', strtotime($program->date)));
                })
                ->editColumn('from_to_time', function (Program $program) {
                    return AppHelperFunctions::getGreenBadge(date('h:i A', strtotime($program->from_time))). '-' .AppHelperFunctions::getGreenBadge(date('h:i A', strtotime($program->to_time)));
                })
                ->addColumn('session_theme', function (Program $program) {
                    return '<a href="' . route('sessiontheme.edit', $program->session_theme_id) . '">' . $program->sessionTheme->theme_name . '</a>';
                })
                ->addColumn('speaker_name', function (Program $program) {
                    return '<a href="' . route('programSpeakers.edit', $program->program_speaker_id) . '">' . $program->programSpeaker->name . '</a>';
                })
                ->addColumn('speaker_image', function (Program $program) {
                    $imageSrc = !empty($program->programSpeaker->getMedia('speaker_image')->first()) ? $program->programSpeaker->getMedia('speaker_image')->first()->getUrl() : '/assets/img/no-image.png';
                    return '<img src="'.$imageSrc.'" width="80px" height="80px">';
                })
                ->editColumn('status', function (Program $program) {
                    $color = array_search($program->status, config('program-status'));
                    return AppHelperFunctions::getBadge($program->status, $color);
                })
                ->addColumn('action', function (Program $program) use($user): string {
                    $link = ($user->id == 1 || $user->hasPermissionTo('Edit Programs')) ?
                        '<a href="' . route('programs.edit', $program->id) . '" class="btn-purple btn mr-1" ><i class="fas fa-edit"></i></a>'
                        : "";
                    $link .= ($user->id == 1 || $user->hasPermissionTo('Delete Programs')) ?
                        '<span data-href="'.route('programs.destroy', $program->id).'" class="btn-purple program-delete btn"><i class="fas fa-trash"></i></span>'
                            : "";
                    return $link;
                })
                ->rawColumns([ 'date', 'from_to_time', 'session_theme' ,'speaker_image', 'speaker_name', 'status', 'action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.programs.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::find(auth()->user()->id);
        if( $user->id != 1 && !$user->hasPermissionTo('Create Programs')){
            abort(403);
        }
        return view('admin.programs.form')->with([
            'program' => new Program(),
            'program_speakers' => ProgramSpeaker::all(),
            'session_themes' => SessionTheme::where('status', 1)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'topic' => 'required',
            'date' => 'required',
            'from_time' => 'required',
            'to_time' => 'required',
            'program_speaker_id' => 'required',
            'session_theme_id' => 'required',
            'status' => 'required',
        ]);
        $sessionTheme = SessionTheme::find($request->session_theme_id);
        if((strtotime($request->from_time) < strtotime($sessionTheme->from_time) ||
            strtotime($request->from_time) > strtotime($sessionTheme->to_time))  &&
            ((strtotime($request->to_time) > strtotime($sessionTheme->to_time)) ||
             (strtotime($request->to_time) < strtotime($sessionTheme->from_time)))
            ){
            return redirect()->back()->with('warning', 'Program time should be within session theme time')->withInput();
        }
        $data['date'] = date('Y-m-d', strtotime($request->date));
        $data['from_time'] = Carbon::parse($data['from_time'])->format('H:i:s');
        $data['to_time'] = Carbon::parse($data['to_time'])->format('H:i:s');
        Program::create($data);
        return redirect()->back()->with('success', 'Program created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Program $program)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Program $program)
    {
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('Edit Programs')){
            abort(403);
        }
        return view('admin.programs.form')->with([
            'program' => $program,
            'program_speakers' => ProgramSpeaker::all(),
            'session_themes' => SessionTheme::where('status', 1)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Program $program)
    {
        $data = $request->validate([
            'topic' => 'required',
            'date' => 'required',
            'from_time' => 'required',
            'to_time' => 'required',
            'program_speaker_id' => 'required',
            'session_theme_id' => 'required',
            'status' => 'required',
        ]);
        $sessionTheme = SessionTheme::find($request->session_theme_id);
        if((strtotime($request->from_time) < strtotime($sessionTheme->from_time) ||
            strtotime($request->from_time) > strtotime($sessionTheme->to_time))  &&
            ((strtotime($request->to_time) > strtotime($sessionTheme->to_time)) ||
             (strtotime($request->to_time) < strtotime($sessionTheme->from_time)))
            ){
            return redirect()->back()->with('warning', 'Program time should be within session theme time')->withInput();
        }
        $data['date'] = date('Y-m-d', strtotime($request->date));
        $data['from_time'] = Carbon::parse($data['from_time'])->format('H:i:s');
        $data['to_time'] = Carbon::parse($data['to_time'])->format('H:i:s');
        $program->update($data);
        return redirect()->back()->with('success', 'Program updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('Delete Programs')){
           return response()->json([
                'message' => "You don't have permission to delete program",
            ], Response::HTTP_BAD_REQUEST);
        }
        $program = Program::find($id);
        if($program->programRegistrations()->count() > 0) {
            return response()->json([
                'message' => "Program can not be deleted as it is associated with registrations",
            ], Response::HTTP_BAD_REQUEST);
        } else {
            $program->delete();
            return response()->json([
                'message' => 'Program deleted successfully',
            ], Response::HTTP_OK);
        }
    }
}
