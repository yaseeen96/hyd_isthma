<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Plank\Mediable\Facades\MediaUploader;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, DataTables $datatable)
    {
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('View Faq')){
            abort(403);
        }
        if($request->ajax()) {
            $query = Faq::query();
            return $datatable->eloquent($query)
                ->addColumn('faq_attachment', function ( Faq $faq) {
                    $imageSrc = !empty($faq->getMedia('faq_attachment')->first()) ? $faq->getMedia('faq_attachment')->first()->getUrl() : 'NA';
                    return  $imageSrc != 'NA' ? '<a href="'.$imageSrc.'" target="_blank">View Attachment</a>' : 'NA';
                })
                ->addColumn('action', function (Faq $faq) use($user) {
                    $link = ($user->id == 1 || $user->hasPermissionTo('Edit Faq')) ?
                        '<a href="' . route('faq.edit', $faq->id) . '" class="btn-purple btn mr-1" ><i class="fas fa-edit"></i></a>'
                        : "";
                    $link .= ($user->id == 1 || $user->hasPermissionTo('Delete ProgramSpeakers')) ?
                        '<span data-href="' . route('faq.destroy', $faq->id) . '" class="btn-purple faq-delete btn"><i class="fas fa-trash"></i></span>'
                        : "";
                    return $link;
                })
                ->rawColumns([ 'faq_attachment', 'action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.faq.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('Create Faq')){
            abort(403);
        }
        return view('admin.faq.form')->with([
            'faq' => new Faq(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('Create Faq')){
            abort(403);
        }
        $request->validate([
            'question' => 'required',
            'faq_attachment' => 'nullable|max:2048',
        ]);
        $faq = new Faq();
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();
         if(!empty($request->file('faq_attachment'))) {
            $media = MediaUploader::fromSource($request->file('faq_attachment'))->toDestination('public', 'images/faq_attachments')->useFilename(Str::uuid())->upload();
            $faq->attachMedia($media, ['faq_attachment']);
        }
        return redirect()->route('faq.index')->with('success', 'Faq created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(faq $faq)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(faq $faq)
    {
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('Edit Faq')){
            abort(403);
        }
        return view('admin.faq.form')->with([
            'faq' => $faq,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, faq $faq)
    {
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('Edit Faq')){
            abort(403);
        }
        $request->validate([
            'question' => 'required',
            'faq_attachment' => 'nullable|max:2048',
        ]);
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();
        if(!empty($request->file('faq_attachment'))) {
            $uploadedImages = $faq->getMedia('faq_attachment')->first();
            if(!empty($uploadedImages)) {
                $faq->detachMedia($faq->id);
                $uploadedImages->delete();
            }
            $media = MediaUploader::fromSource($request->file('faq_attachment'))->toDestination('public', 'images/faq_attachments')->useFilename(Str::uuid())->upload();
            $faq->attachMedia($media, ['faq_attachment']);
        }
        return redirect()->route('faq.edit', $faq->id)->with('success', 'Faq updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(faq $faq)
    {
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('Delete Faq')){
            abort(403);
        }
        $faq->getMedia('faq_attachment')->each->delete();
        $faq->delete();
        return response()->json([
                'message' => "Faq deleted successfully",
                'status' => 200
            ], Response::HTTP_OK);
    }
}