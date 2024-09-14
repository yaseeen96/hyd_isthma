<?php

namespace App\Http\Controllers;

use App\Models\AudioProcessing;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class AudioProcessingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, DataTables $dataTables)
    {
        if ($request->ajax()) {
            $query = AudioProcessing::query();
            return $dataTables->eloquent($query)
                ->addColumn('action', function (AudioProcessing $audioProcessing) {
                    return '<a href="' . route('audioProcessing.edit', $audioProcessing->id) . '" class="btn btn-sm btn-primary btn-clean btn-icon mr-2" title="Edit Translation & Transcript"><i class="fas fa-edit"></i></a>';
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.audioprocessing.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.audioprocessing.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // $pythonScriptPath = '/home/username/scripts/script.py';
        // $output = shell_exec("python " . escapeshellarg($pythonScriptPath));
        $uuid = Str::uuid();
        $output = null;
        sleep(20);
        // $output = shell_exec("python translate_ur_to_other_languages.py translation_conf.cfg youtube_url te ta $uuid");
        // if($output == null) {
        //     return response()->json([
        //         'message' => 'Python script executed',
        //         'output' => 'No output',
        //         'status' => 500
        //     ]);
        // }
        $data = [
            'youtube_url' => $request->youtube_url,
            'language_1' => $request->language_1,
            'language_2' => $request->language_2,
            'directory_prefix' => $uuid
        ];
        $audioProcessing = AudioProcessing::create($data);
        return response()->json([
            'message' => 'Python script executed',
            'output' => $output,
            'redirect_url' => route('audioProcessing.edit', $audioProcessing->id),
            'status' => 200
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $english_text_file_path  = asset('audioprocessing/input_urdu_audio_file_path_english.txt');
        $telugu_transcript_file_path = asset('audioprocessing/input_urdu_audio_file_path_telugu.txt');
        $tamil_transcript_file_path = asset('audioprocessing/input_urdu_audio_file_path_tamil.txt');
        $english_transcript = file_get_contents($english_text_file_path);
        $telugu_transcript = file_get_contents($telugu_transcript_file_path);
        $tamil_transcript = file_get_contents($tamil_transcript_file_path);
        return view('admin.audioprocessing.edit')->with(
            [
                'audioProcessing' => AudioProcessing::find($id),
                'english_transcript' => $english_transcript,
                'telugu_transcript' => $telugu_transcript,
                'tamil_transcript' => $tamil_transcript
            ]
        );
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