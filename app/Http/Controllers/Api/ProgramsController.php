<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramListResource;
use App\Http\Resources\SessionThemeListResource;
use App\Models\Program;
use App\Models\ProgramSpeaker;
use App\Models\SessionRegistration;
use App\Models\SessionTheme;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;


class ProgramsController extends Controller
{
    public function listPrograms(Request $request) {
        $lang = $request->lang ?? '';
        $image_placeholder = env('APP_URL') . '/assets/img/no-image.png';

        // Cache sessions and eager load necessary relationships
        $sessions = Cache::remember('sessions', 60, function () use ($lang) {
            return SessionTheme::with([
                'programs.programSpeaker' => function ($query) {
                    $query->withMedia('speaker_image');
                },
                'sessionRegistrations' => function ($query) {
                    $query->where('member_id', auth()->id());
                }
            ])->get();
        });

        $speakers = ProgramSpeaker::withMedia('speaker_image')->get()->keyBy('id'); // Preload all speakers
        $data = [];
        foreach ($sessions as $session) {
            $speaker_details = $speakers->filter(function ($speaker) use ($session) {
                return stripos($speaker->name, $session->convener) !== false;
            })->first() ?? '';
            $speaker_image_url = $speaker_details && $speaker_details->firstMedia('speaker_image')
                ? $speaker_details->firstMedia('speaker_image')->getUrl()
                : $image_placeholder;

            $programs = [];
            foreach ($session->programs as $program) {
                $program_speaker =  !empty($program->program_speaker_id) && !empty($speaker_details) ? ($program->programSpeaker->id == $speaker_details->id
                    ? $speaker_details
                    : ($speakers[$program->program_speaker_id] ?? null)) : null;

                $programs[] = [
                    'id' => $program->id,
                    'name' => $lang ? $program->{$lang . '_topic'} : $program->topic,
                    'url' => $lang ? $program->{$lang . '_url'} : $program->url,
                    'datetime' => $program->from_time && $program->to_time ?
                        date('Y-m-d', strtotime($program->date)) . ' ' . Carbon::parse($program->from_time)->format('h:i A'). ' - ' . Carbon::parse($program->to_time)->format('h:i A')
                        : null,
                    'speaker_name' => $program_speaker ? ($lang ? $program_speaker->{$lang . '_name'} : $program_speaker->name) : null,
                    'speaker_bio' => $program_speaker ? ($lang ? $program_speaker->{$lang . '_bio'} : $program_speaker->bio) : null,
                    'speaker_image' => $program_speaker ?
                        ($program_speaker->getMedia('speaker_image')->first() ? $program_speaker->getMedia('speaker_image')->first()->getUrl() : $image_placeholder)
                        : null,
                    'status' => $program->status,
                    'translation' => '',
                    'transcript' => $lang ? $program->{$lang . '_transcript'} : null,
                ];
            }

            $data[] = [
                'id' => $session->id,
                'theme_name' => $lang ? $session->{$lang . '_theme_name'} : $session->theme_name,
                'session_convener' => $session->convener ? ($lang ? ($speaker_details->{$lang . '_name'} ?? '') : $session->convener) : '',
                'convener_bio' => $session->convener ? ($lang ? ($speaker_details->{$lang . '_bio'} ?? '') : '') : '',
                'convener_image' => $speaker_image_url,
                'theme_type' => ucfirst($session->theme_type),
                'hall_name' => $session->hall_name,
                'datetime' => date('Y-m-d', strtotime($session->date)) . ' ' . Carbon::parse($session->from_time)->format('h:i A') . ' - ' . Carbon::parse($session->to_time)->format('h:i A'),
                'status' => $session->status,
                'enrolled' => $session->sessionRegistrations->isNotEmpty(),
                'programs' => $programs,
            ];
        }

        return response()->json([
            "data" => $data
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
        Cache::forget('sessions');
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