<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramListResource;
use App\Http\Resources\SessionThemeListResource;
use App\Models\Program;
use App\Models\SessionRegistration;
use App\Models\SessionTheme;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class ProgramsController extends Controller
{
    public function listPrograms() {
        $sessions = SessionTheme::all();
        return response()->json([
            "data" => SessionThemeListResource::collection($sessions),
        ]);
    }
    public function registerSession(Request $request) {
        $user = auth()->user();
        $request->validate([
            'session_id' => 'required|exists:session_themes,id',
        ]);
        //1. check if user is already enrolled in the session
        $userEnrolledSessions = SessionRegistration::where('member_id', $user->id)->pluck('session_id')->toArray();
        //2. if already enrolled, getting list of session deatils.
        $sessions = SessionTheme::where('theme_type', 'parallel')->whereIn('id', $userEnrolledSessions)->get();
        //3. get current requested session details.
        $requestedSession = SessionTheme::find($request->session_id);
        //4. checking if user is already enrolled in the requested session
        if(in_array($requestedSession->id, $userEnrolledSessions)) {
            return response()->json([
                'message' => 'You have already enrolled in this Session',
                'status' => 'failure'
            ], Response::HTTP_BAD_REQUEST);
        }
        //5. checking if user is already enrolled in a parallel session with the same time slot as the requested session
        $newParallelEnrollment = SessionTheme::where('theme_type', 'parallel')->where('id', $request->session_id)->whereDate('date', $requestedSession->date)->first();
        if($newParallelEnrollment && $sessions) {
            foreach($sessions as $session) {
                if(Carbon::parse($newParallelEnrollment->form_time)->between(Carbon::parse($session->from_time), Carbon::parse($session->to_time)) || Carbon::parse($newParallelEnrollment->to_time)->between(Carbon::parse($session->from_time), Carbon::parse($session->to_time))) {
                   return response()->json([
                        'message' => 'You have already enrolled in a parallel session for this time slot',
                        'status' => 'failure',
                        'data' => [
                            'session' => $session->from_time.'-'.$session->to_time,
                            'enrolling_session' => $newParallelEnrollment->from_time.'-'.$newParallelEnrollment->to_time
                        ]
                    ], Response::HTTP_BAD_REQUEST);
                }
            }
        }
        //6. enrolling the user to requested program
        SessionRegistration::updateOrCreate(['member_id' => $user->id, 'session_id' => $request->session_id],[
            'member_id' => $user->id,
            'session_id' => $request->session_id
        ]);
        return response()->json([
            'message' => 'Registered for session successfully',
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
