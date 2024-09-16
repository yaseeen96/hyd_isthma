<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class QrCodeOperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, DataTables $datatable)
    {
        if (auth()->user()->id != 1 && !auth()->user()->hasPermissionTo('View QrOperators')){
            abort(403);
        }
        if($request->ajax()) {
            $query = User::query()->where('id', '!=', 1)->role(['Qr Operator']);
            return $datatable->eloquent($query)
                ->addColumn('action', function (User $user) {
                    $link = (auth()->user()->id == 1 || auth()->user()->hasPermissionTo('Edit QrOperators')) ?
                        '<a href="' . route('qrOperators.edit', $user->id) . '" class="btn-purple btn mr-1" ><i class="fas fa-edit"></i></a>'
                        : "";
                    $link .= auth()->user()->id == 1 || auth()->user()->hasPermissionTo('Delete QrOperators') ?
                            '<span data-href="'.route('qrOperators.destroy', $user->id).'" class="btn-purple user-delete btn"><i class="fas fa-trash"></i></span>'
                            : "";
                    return $link;
                })
                ->rawColumns([ 'action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.qroperators.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('Create QrOperators')){
            abort(403);
        }
        return view('admin.qroperators.form')->with([
            'operator' => new User(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone_number' => 'required|unique:users,phone_number'
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = !empty($request->email) ? $request->email : Str::uuid().'@gmail.com';
        $user->password = bcrypt(Str::random(8));
        $user->phone_number = $request->phone_number;
        $user->save();
        $user->assignRole('Qr Operator');
        return redirect()->route('qrOperators.index')->with('success', 'Qr Operator created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $loggedInUser = User::find(auth()->user()->id);
        if ($loggedInUser->id != 1 && !$loggedInUser->hasPermissionTo('Edit QrOperators')){
            abort(403);
        }
        return view('admin.qroperators.form')->with([
            'operator' =>  User::find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => 'required',
            'phone_number' => 'required',
        ]);
        $user = User::find($id);
        $user->update($data);
        return redirect()->route('qrOperators.index')->with('success', 'Qr Operator Details updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('Delete QrOperators')){
            abort(403);
        }
        $user = User::where('id', $id)->first();
        $user->delete();
        return response()->noContent();
    }
}