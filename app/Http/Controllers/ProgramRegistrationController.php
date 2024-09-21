<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ProgramRegistration;
use App\Models\ProgramSpeaker;
use App\Models\SessionTheme;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProgramRegistrationController extends Controller
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
            $query = ProgramRegistration::with('program', 'member')->whereHas('program', function($query) use($request) {
                if(!empty($request->program_name))
                    $query->where('id', $request->program_name);
                if(!empty($request->program_speaker))
                    $query->where('program_speaker_id', $request->program_speaker);
                if(!empty($request->session_theme))
                    $query->where('session_theme_id', $request->session_theme);
            })->wherehas('program.sessionTheme', function($query) use($request) {
                if(!empty($request->theme_type))
                    $query->where('theme_type', $request->theme_type);
            })->orderBy('id', 'desc');
            return $dataTables->eloquent($query)
                ->addColumn('speaker', function (ProgramRegistration $programRegistration) {
                    return $programRegistration->program->programSpeaker->name;
                })
                ->addColumn('speaker_image', function (ProgramRegistration $programRegistration) {
                    $imageSrc = !empty($programRegistration->program->programSpeaker->getMedia('speaker_image')->first()) ? $programRegistration->program->programSpeaker->getMedia('speaker_image')->first()->getUrl() : '/assets/img/no-image.png';
                    return '<img src="' . $imageSrc . '" width="80px" height="80px" class="rounded-circle">';
                })
                ->addColumn('session_theme', function (ProgramRegistration $programRegistration) {
                    return $programRegistration->program->sessionTheme->theme_name;
                })
                ->addColumn('theme_type', function (ProgramRegistration $programRegistration) {
                    return $programRegistration->program->sessionTheme->theme_type;
                })
                ->addIndexColumn()
                ->rawColumns(['speaker', 'speaker_image'])
                ->make(true);
        }
        return view('admin.programregistration.list')->with(
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
        //
    }
}
