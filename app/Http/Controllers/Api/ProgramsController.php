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
