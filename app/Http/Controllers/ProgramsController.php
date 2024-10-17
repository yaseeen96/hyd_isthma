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
use Plank\Mediable\Facades\MediaUploader;
use Illuminate\Support\Str;

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
                    return AppHelperFunctions::getGreenBadge(date('d-m-Y', strtotime($program->sessionTheme->date)));
                })
                ->editColumn('from_to_time', function (Program $program) {
                    return AppHelperFunctions::getGreenBadge(date('h:i A', strtotime($program->from_time))) . '-' . AppHelperFunctions::getGreenBadge(date('h:i A', strtotime($program->to_time)));
                })
                ->addColumn('session_theme', function (Program $program) {
                    return '<a href="' . route('sessiontheme.edit', $program->session_theme_id) . '">' . $program->sessionTheme->theme_name . '</a>';
                })
                ->addColumn('speaker_name', function (Program $program) {
                    return '<a href="' . route('programSpeakers.edit', $program->program_speaker_id) . '">' . $program->programSpeaker->name . '</a>';
                })
                ->addColumn('speaker_image', function (Program $program) {
                    $imageSrc = !empty($program->programSpeaker->getMedia('speaker_image')->first()) ? $program->programSpeaker->getMedia('speaker_image')->first()->getUrl() : '/assets/img/no-image.png';
                    return '<img src="' . $imageSrc . '" width="80px" height="80px">';
                })
                ->editColumn('status', function (Program $program) {
                    $color = array_search($program->status, config('program-status'));
                    return AppHelperFunctions::getBadge($program->status, $color);
                })
                // ->editColumn('program_copy', function (Program $program) {
                //     $programCopy = $program->getMedia('program_copy')->first();
                //     return !empty($programCopy) ? '<a class="badge badge-primary" href="' . $programCopy->getUrl() . '" target="_blank">View</a>' : '';
                // })
                ->editColumn('english_transcript', function (Program $program) {
                    $englishTranscript = $program->english_transcript;
                    return !empty($englishTranscript) ? substr($englishTranscript, 0, 100) . '...' : '';
                })
                ->editColumn('english_translation', function (Program $program) {
                    $englishTranslation = $program->getMedia('english_translation')->first();
                    return !empty($englishTranslation) ? '<a class="badge badge-primary" href="' . $englishTranslation->getUrl() . '" target="_blank">View</a>' : '';
                })
                // ->editColumn('english_program_copy', function(Program $program) {
                //     $englishProgramCopy = $program->getMedia('english_program_copy')->first();
                //     return !empty($englishProgramCopy) ? '<a class="badge badge-primary" href="' . $englishProgramCopy->getUrl() . '" target="_blank">View</a>' : '';
                // })
                ->editColumn('malayalam_transcript', function (Program $program) {
                    $malayalamTranscript = $program->malyalam_transcript;
                    return !empty($malayalamTranscript) ? substr($malayalamTranscript, 0, 100) . '...' : '';
                })
                ->editColumn('malayalam_translation', function (Program $program) {
                    $malayalamTranslation = $program->getMedia('malyalam_translation')->first();
                    return !empty($malayalamTranslation) ? '<a class="badge badge-primary" href="' . $malayalamTranslation->getUrl() . '" target="_blank">View</a>' : '';
                })
                // ->editColumn('malyalam_program_copy', function(Program $program) {
                //     $malayalamProgramCopy = $program->getMedia('malyalam_program_copy')->first();
                //     return !empty($malayalamProgramCopy) ? '<a class="badge badge-primary" href="' . $malayalamProgramCopy->getUrl() . '" target="_blank">View</a>' : '';
                // })
                ->editColumn('bengali_transcript', function (Program $program) {
                    $bengaliTranscript = $program->bengali_transcript;
                    return !empty($bengaliTranscript) ? substr($bengaliTranscript, 0, 100) . '...' : '';
                })
                ->editColumn('bengali_translation', function (Program $program) {
                    $bengaliTranslation = $program->getMedia('bengali_translation')->first();
                    return !empty($bengaliTranslation) ? '<a class="badge badge-primary" href="' . $bengaliTranslation->getUrl() . '" target="_blank">View</a>' : '';
                })
                // ->editColumn('bengali_program_copy', function(Program $program) {
                //     $bengaliProgramCopy = $program->getMedia('bengali_program_copy')->first();
                //     return !empty($bengaliProgramCopy) ? '<a class="badge badge-primary" href="' . $bengaliProgramCopy->getUrl() . '" target="_blank">View</a>' : '';
                // })
                ->editColumn('tamil_transcript', function (Program $program) {
                    $tamilTranscript = $program->tamil_transcript;
                    return !empty($tamilTranscript) ? substr($tamilTranscript, 0, 100) . '...' : '';
                })
                ->editColumn('tamil_translation', function (Program $program) {
                    $tamilTranslation = $program->getMedia('tamil_translation')->first();
                    return !empty($tamilTranslation) ? '<a class="badge badge-primary" href="' . $tamilTranslation->getUrl() . '" target="_blank">View</a>' : '';
                })
                // ->editColumn('tamil_program_copy', function(Program $program) {
                //     $tamilProgramCopy = $program->getMedia('tamil_program_copy')->first();
                //     return !empty($tamilProgramCopy) ? '<a class="badge badge-primary" href="' . $tamilProgramCopy->getUrl() . '" target="_blank">View</a>' : '';
                // })
                ->editColumn('kannada_transcript', function (Program $program) {
                    $kannadaTranscript =$program->kannada_transcript;
                    return !empty($kannadaTranscript) ? substr($kannadaTranscript, 0, 100) . '...' : '';
                })
                ->editColumn('kannada_translation', function (Program $program) {
                    $kannadaTranslation = $program->getMedia('kannada_translation')->first();
                    return !empty($kannadaTranslation) ? '<a class="badge badge-primary" href="' . $kannadaTranslation->getUrl() . '" target="_blank">View</a>' : '';
                })
                // ->editColumn('kannada_program_copy', function(Program $program) {
                //     $kannadaProgramCopy = $program->getMedia('kannada_program_copy')->first();
                //     return !empty($kannadaProgramCopy) ? '<a class="badge badge-primary" href="' . $kannadaProgramCopy->getUrl() . '" target="_blank">View</a>' : '';
                // })
                ->addColumn('action', function (Program $program) use($user): string {
                    $link = ($user->id == 1 || $user->hasPermissionTo('Edit Programs')) ?
                        '<a href="' . route('programs.edit', $program->id) . '" class="btn-purple btn mr-1" ><i class="fas fa-edit"></i></a>'
                        : "";
                    $link .= ($user->id == 1 || $user->hasPermissionTo('Delete Programs')) ?
                        '<span data-href="'.route('programs.destroy', $program->id).'" class="btn-purple program-delete btn"><i class="fas fa-trash"></i></span>'
                            : "";
                    return $link;
                })
                ->rawColumns([ 'date', 'from_to_time', 'session_theme'
                        ,'speaker_image', 'speaker_name', 'status', 'action',
                        // 'program_copy',
                        'english_transcript', 'english_translation',
                        //  'english_program_copy',
                        'malayalam_transcript', 'malayalam_translation',
                        // 'malyalam_program_copy',
                        'bengali_transcript', 'bengali_translation',
                        // 'bengali_program_copy',
                        'tamil_transcript', 'tamil_translation',
                        // 'tamil_program_copy',
                        'kannada_transcript', 'kannada_translation',
                        // 'kannada_program_copy'
                        ])
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
            'session_themes' => SessionTheme::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'topic' => 'required',
            'from_time' => 'required',
            'to_time' => 'required',
            'program_speaker_id' => 'required',
            'session_theme_id' => 'required',
            'status' => 'required',
        ]);
        $sessionTheme = SessionTheme::find($request->session_theme_id);
        if((strtotime($request->from_time) < strtotime($sessionTheme->from_time) ||
            strtotime($request->from_time) > strtotime($sessionTheme->to_time))  ||
            ((strtotime($request->to_time) > strtotime($sessionTheme->to_time)) ||
             (strtotime($request->to_time) < strtotime($sessionTheme->from_time)))
            ){
            return redirect()->back()->with('warning', 'Program time should be within session theme time')->withInput();
        }
        $data['date'] = date('Y-m-d', strtotime($sessionTheme->date));
        $data['from_time'] = Carbon::parse($data['from_time'])->format('H:i:s');
        $data['to_time'] = Carbon::parse($data['to_time'])->format('H:i:s');
        $data['english_transcript'] = $request->english_transcript;
        $data['malyalam_transcript'] = $request->malyalam_transcript;
        $data['bengali_transcript'] = $request->bengali_transcript;
        $data['tamil_transcript'] = $request->tamil_transcript;
        $data['kannada_transcript'] = $request->kannada_transcript;
        $data['english_topic'] = $request->english_topic;
        $data['malyalam_topic'] = $request->malyalam_topic;
        $data['bengali_topic'] = $request->bengali_topic;
        $data['tamil_topic'] = $request->tamil_topic;
        $data['kannada_topic'] = $request->kannada_topic;

        $program = Program::create($data);
        // Urudu Language Program Copy
        // if($request->hasFile('program_copy')) {
        //     $media = MediaUploader::fromSource($request->file('program_copy'))->toDestination('public', "program_copies/urdu/$program->id")->useFilename(Str::uuid())->upload();
        //     $program->attachMedia($media, ['program_copy']);
        // }
        // // English Language Program Copy
        // if($request->hasFile('english_program_copy')) {
        //     $media = MediaUploader::fromSource($request->file('english_program_copy'))->toDestination('public', "program_copies/english/$program->id")->useFilename(Str::uuid())->upload();
        //     $program->attachMedia($media, ['english_program_copy']);
        // }

        // // Malayalam Language Program Copy
        // if($request->hasFile('malyalam_program_copy')) {
        //     $media = MediaUploader::fromSource($request->file('malyalam_program_copy'))->toDestination('public', "program_copies/malyalam/$program->id")->useFilename(Str::uuid())->upload();
        //     $program->attachMedia($media, ['malyalam_program_copy']);
        // }

        // // Bengali Language Program Copy
        // if($request->hasFile('bengali_program_copy')) {
        //     $media = MediaUploader::fromSource($request->file('bengali_program_copy'))->toDestination('public', "program_copies/bengali/$program->id")->useFilename(Str::uuid())->upload();
        //     $program->attachMedia($media, ['bengali_program_copy']);
        // }

        // // Tamil Language Program Copy
        // if($request->hasFile('tamil_program_copy')) {
        //     $media = MediaUploader::fromSource($request->file('tamil_program_copy'))->toDestination('public', "program_copies/tamil/$program->id")->useFilename(Str::uuid())->upload();
        //     $program->attachMedia($media, ['tamil_program_copy']);
        // }

        // // Kannada Language Program Copy
        // if($request->hasFile('kannada_program_copy')) {
        //     $media = MediaUploader::fromSource($request->file('kannada_program_copy'))->toDestination('public', "program_copies/kannada/$program->id")->useFilename(Str::uuid())->upload();
        //     $program->attachMedia($media, ['kannada_program_copy']);
        // }

        // English Language Program translation
        if($request->hasFile('english_translation')) {
            $media = MediaUploader::fromSource($request->file('english_translation'))->toDestination('public', "program_translations/english/$program->id")->useFilename(Str::uuid())->upload();
            $program->attachMedia($media, ['english_translation']);
        }

        // Malayalam Language Program translation
        if($request->hasFile('malyalam_translation')) {
            $media = MediaUploader::fromSource($request->file('malyalam_translation'))->toDestination('public', "program_translations/malyalam/$program->id")->useFilename(Str::uuid())->upload();
            $program->attachMedia($media, ['malyalam_translation']);
        }

        // Bengali Language Program translation
        if ($request->hasFile('bengali_translation')) {
            $media = MediaUploader::fromSource($request->file('bengali_translation'))->toDestination('public', "program_translations/bengali/$program->id")->useFilename(Str::uuid())->upload();
            $program->attachMedia($media, ['bengali_translation']);
        }

        // Tamil Language Program translation
        if ($request->hasFile('tamil_translation')) {
            $media = MediaUploader::fromSource($request->file('tamil_translation'))->toDestination('public', "program_translations/tamil/$program->id")->useFilename(Str::uuid())->upload();
            $program->attachMedia($media, ['tamil_translation']);
        }

        // Kannada Language Program translation
        if ($request->hasFile('kannada_translation')) {
            $media = MediaUploader::fromSource($request->file('kannada_translation'))->toDestination('public', "program_translations/kannada/$program->id")->useFilename(Str::uuid())->upload();
            $program->attachMedia($media, ['kannada_translation']);
        }
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
            'session_themes' => SessionTheme::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Program $program)
    {
        $data = $request->validate([
            'topic' => 'required',
            'from_time' => 'required',
            'to_time' => 'required',
            'program_speaker_id' => 'required',
            'session_theme_id' => 'required',
            'status' => 'required',
        ]);
        $sessionTheme = SessionTheme::find($request->session_theme_id);
        if((strtotime($request->from_time) < strtotime($sessionTheme->from_time) ||
            strtotime($request->from_time) > strtotime($sessionTheme->to_time))  ||
            ((strtotime($request->to_time) > strtotime($sessionTheme->to_time)) ||
             (strtotime($request->to_time) < strtotime($sessionTheme->from_time)))
            ){
            return redirect()->back()->with('warning', 'Program time should be within session theme time')->withInput();
        }
        $data['date'] = date('Y-m-d', strtotime($sessionTheme->date));
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
            $program->media()->each(function ($media) {
                $media->delete();
            });
            return response()->json([
                'message' => 'Program deleted successfully',
            ], Response::HTTP_OK);
        }
    }
}