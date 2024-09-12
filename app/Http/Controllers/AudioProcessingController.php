<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AudioProcessingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.audioprocessing.index');
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

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $pythonScriptPath = '/home/username/scripts/script.py';
        // $output = shell_exec("python " . escapeshellarg($pythonScriptPath));
        $output = shell_exec("python translate_ur_to_other_languages.py translation_conf.cfg /home/kkshanamlc/public_html/python_code/input_urdu_audio_file_path.mp3 te ta");
        dd($output);
        return response()->json([
            'message' => 'Python script executed',
            'output' => $output
        ]);
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