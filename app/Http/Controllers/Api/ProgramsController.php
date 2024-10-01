<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramListResource;
use App\Models\Member;
use App\Models\Program;
use App\Models\ProgramRegistration;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class ProgramsController extends Controller
{
    public function listPrograms() {
        $programs = Program::with('sessionTheme', 'programSpeaker')->paginate();
        return response()->json([
            "data" => ProgramListResource::collection($programs),
            "meta" => [
                "current_page" => $programs->currentPage(),
                "last_page" => $programs->lastPage(),
                "per_page" => $programs->perPage(),
                "total" => $programs->total(),
                "next_page_url" => $programs->nextPageUrl(),
                "prev_page_url" => $programs->previousPageUrl(),
            ]
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
