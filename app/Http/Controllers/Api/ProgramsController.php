<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramListResource;
use App\Http\Resources\SessionThemeListResource;
use App\Models\Member;
use App\Models\Program;
use App\Models\ProgramRegistration;
use App\Models\SessionTheme;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class ProgramsController extends Controller
{
    public function listPrograms() {
        $sessions = SessionTheme::all();
        return response()->json([
            "data" => SessionThemeListResource::collection($sessions),
            // "meta" => [
            //     "current_page" => $sessions->currentPage(),
            //     "last_page" => $sessions->lastPage(),
            //     "per_page" => $sessions->perPage(),
            //     "total" => $sessions->total(),
            //     "next_page_url" => $sessions->nextPageUrl(),
            //     "prev_page_url" => $sessions->previousPageUrl(),
            // ]
        ]);
    }
    public function registerProgram(Request $request) {
        $user = auth()->user();
        $request->validate([
            'program_id' => 'required|exists:programs,id',
        ]);
        //1. check if user is already enrolled in the program
        $userEnrolledPrograms = ProgramRegistration::where('member_id', $user->id)->pluck('program_id')->toArray();
        //2. if already enrolled, getting list of program deatils.
        $programs = Program::with('sessionTheme')->whereHas('sessionTheme', function($query) {
            $query->where('theme_type', 'parallel');
        })->whereIn('id', $userEnrolledPrograms)->get();
        //3. get current requested program details.
        $requestedProgram = Program::find($request->program_id);
        //4. checking if user is already enrolled in the requested program
        if(in_array($requestedProgram->id, $userEnrolledPrograms)) {
            return response()->json([
                'message' => 'You have already enrolled in this program',
                'status' => 'failure'
            ], Response::HTTP_BAD_REQUEST);
        }
        //5. checking if user is already enrolled in a parallel session with the same time slot as the requested program
        $newParallelEnrollment = Program::with('sessionTheme')->whereHas('sessionTheme', function ($query) {
            $query->where('theme_type', 'parallel');
        })->where('id', $request->program_id)->whereDate('date', $requestedProgram->date)->first();
        if($newParallelEnrollment && $programs) {
            foreach($programs as $program) {
                if($newParallelEnrollment->from_time >= $program->to_time || $newParallelEnrollment->to_time <= $program->to_time ) {
                    // do nothing
                } else {
                    return response()->json([
                        'message' => 'You have already enrolled in a parallel session for this time slot',
                        'status' => 'failure',
                        'data' => [
                            'program' => $program->from_time.'-'.$program->to_time,
                            'enrolling_program' => $newParallelEnrollment->from_time.'-'.$newParallelEnrollment->to_time
                        ]
                    ], Response::HTTP_BAD_REQUEST);
                }
            }
        }
        //6. enrolling the user to requested program
        ProgramRegistration::updateOrCreate(['member_id' => $user->id, 'program_id' => $request->program_id],[
            'member_id' => $user->id,
            'program_id' => $request->program_id
        ]);
        return response()->json([
            'message' => 'Program registered successfully',
            'status' => 'success'
        ], Response::HTTP_OK);
    }

    public function getProgram(Request $request, $id) {
        $program = Program::with('sessionTheme', 'programSpeaker')->find($id);
        return response()->json([
            'data' => new ProgramListResource($program)
        ]);
    }
}