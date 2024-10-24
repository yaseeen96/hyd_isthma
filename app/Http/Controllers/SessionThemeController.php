<?php

namespace App\Http\Controllers;

use App\Models\ProgramSpeaker;
use App\Models\SessionTheme;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Response;
use App\Helpers\AppHelperFunctions;

class SessionThemeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, DataTables $datatable)
    {
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('View SessionThemes')){
            abort(403);
        }
        if($request->ajax()) {
            $query = SessionTheme::query();
            return $datatable->eloquent($query)
                ->editColumn('status', function (SessionTheme $theme) {
                    $color = array_search($theme->status, config('program-status'));
                    return AppHelperFunctions::getBadge($theme->status, $color);
                })
                ->editColumn('date', function (SessionTheme $theme) {
                    return AppHelperFunctions::getGreenBadge(date('d-m-Y', strtotime($theme->date)));
                })
                ->editColumn('from_to_time', function (SessionTheme $theme) {
                    return AppHelperFunctions::getGreenBadge(date('h:i A', strtotime($theme->from_time))). '-'.AppHelperFunctions::getGreenBadge(date('h:i A', strtotime($theme->to_time)));
                })
                ->addColumn('action', function (SessionTheme $theme) use($user) {
                    $link = ($user->id == 1 || $user->hasPermissionTo('Edit SessionThemes')) ?
                        '<a href="' . route('sessiontheme.edit', $theme->id) . '" class="btn-purple btn mr-1" ><i class="fas fa-edit"></i></a>'
                        : "";
                    $link .= ($user->id == 1 || $user->hasPermissionTo('Delete SessionThemes')) ?
                        '<span data-href="'.route('sessiontheme.destroy', $theme->id).'" class="btn-purple sessiontheme-delete btn"><i class="fas fa-trash"></i></span>'
                            : "";
                    return $link;
                })
                ->rawColumns([  'action', 'date', 'from_to_time', 'to_time','status'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.sessiontheme.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('Create SessionThemes')){
            abort(403);
        }
        return view('admin.sessiontheme.form')->with([
            'theme' => new SessionTheme(),
            'conveners' => ProgramSpeaker::all()->pluck('name')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'theme_name' => 'required',
            'theme_type' => 'required',
            'english_theme_name' => 'required',
            'malyalam_theme_name' => 'required',
            'bengali_theme_name' => 'required',
            'tamil_theme_name' => 'required',
            'kannada_theme_name' => 'required',
            'date' => 'required',
            'from_time' => 'required',
            'to_time' => 'required',
            'status' => 'required',
        ]);
        $data['hall_name'] = $request->get("hall_name");
        $data['convener'] = $request->get("convener");
        if(strtotime($request->to_time) <= strtotime($request->from_time)) {
           return redirect()->back()->with('warning', "To time can't be less then From time")->withInput();
        }
        $data['from_time'] = Carbon::parse($data['from_time'])->format('H:i:s');
        $data['to_time'] = Carbon::parse($data['to_time'])->format('H:i:s');
        SessionTheme::create($data);
        return redirect()->route('sessiontheme.index')->with('success', 'Theme Session created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(SessionTheme $sessionTheme)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $loggedInUser = User::find(auth()->user()->id);
        if ($loggedInUser->id != 1 && !$loggedInUser->hasPermissionTo('Edit SessionThemes')){
            abort(403);
        }
        return view('admin.sessiontheme.form')->with([
           'theme' => SessionTheme::find($id),
           'conveners' => ProgramSpeaker::all()->pluck('name')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $sessionTheme = SessionTheme::find($id);
        $data = $request->validate([
            'theme_name' => 'required',
            'english_theme_name' => 'required',
            'malyalam_theme_name' => 'required',
            'bengali_theme_name' => 'required',
            'tamil_theme_name' => 'required',
            'kannada_theme_name' => 'required',
            'theme_type' => 'required',
            'date' => 'required',
            'from_time' => 'required',
            'to_time' => 'required',
            'status' => 'required',
        ]);
        $data['hall_name'] = $request->get("hall_name");
        $data['convener'] = $request->get("convener");
        if(strtotime($request->to_time) <= strtotime($request->from_time)) {
           return redirect()->back()->with('warning', "To time can't be less then From time")->withInput();
        }
        $data['from_time'] = Carbon::parse($data['from_time'])->format('H:i:s');
        $data['to_time'] = Carbon::parse($data['to_time'])->format('H:i:s');
        $sessionTheme->update($data);
        return redirect()->route('sessiontheme.index')->with('success', 'Theme session updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sessionTheme = SessionTheme::find($id);
        if($sessionTheme->programs()->count() > 0) {
            return response()->json([
                'message' => 'Theme session can not be deleted as it is associated with programs',
            ], Response::HTTP_BAD_REQUEST);
        } else {
            $sessionTheme->delete();
            return response()->json([
                'message' => 'Session theme deleted successfully!',
            ], Response::HTTP_OK);
        }
    }
}