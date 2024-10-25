<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FeedbackController extends Controller
{
    public function submitFeedback(Request $request) {
        $request->validate([
            'feedback_type' => 'required|string',
            'member_id' => 'required|integer',
            'title' => 'nullable|string',
            'description' => 'required|string',
        ]);
        if($request->feedback_type == 'program') {
            $request->validate([
                'program_id' => 'required|integer',
            ]);
        }
        $feedback = new Feedback();
        $feedback->feedback_type = $request->feedback_type;
        $feedback->program_id = $request->program_id;
        $feedback->member_id = $request->member_id;
        $feedback->datetime = $request->datetime;
        $feedback->title = !empty($request->title) ?? 'NA';
        $feedback->description = $request->description;
        $feedback->datetime = now();
        $feedback->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Feedback submitted successfully',
        ], Response::HTTP_OK);
    }
}
