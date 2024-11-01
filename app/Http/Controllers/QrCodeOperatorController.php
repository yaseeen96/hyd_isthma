<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;

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

    /**
     * Bulk Upload
    */
    public function bulkUpload(Request $request)
    {
        // csv file type validation
        $request->validate([
            'qroperators_bulkupload' => 'required'
        ]);
        if($request->file('qroperators_bulkupload')->getClientOriginalExtension() != 'csv'){
            return redirect()->route('qrOperators.index')->with('error', 'Only CSV files are allowed');
        }
        if ($request->hasFile('qroperators_bulkupload')) {
            $csv = Reader::createFromPath($request->file('qroperators_bulkupload')->getPathname(), 'r');
            $csv->setHeaderOffset(0);
            $records = $csv->getRecords();
            $headers = $csv->getHeader();
            DB::beginTransaction();
            if (empty($records)) {
                return redirect()->route('qrOperators.index')->with('error', 'No records found in the uploaded file');
            }
            try {

                foreach ($records as $offset => $record) {
                    if (empty($record['name']) || empty($record['phone_number'])) {
                        throw new \Exception('Name and Phone Number are required');
                    }
                    if (User::where('phone_number', $record['phone_number'])->exists()) {
                        throw new \Exception("Phone Number {$record['phone_number']} already exists");
                    }
                    $user = new User();
                    $user->name = $record['name'];
                    $user->email = !empty($record['email']) ? $record['email'] : Str::uuid() . '@gmail.com';
                    $user->password = bcrypt(Str::random(8));
                    $user->phone_number = $record['phone_number'];
                    $user->save();
                    $user->assignRole('Qr Operator');
                }
                DB::commit();
                return redirect()->route('qrOperators.index')->with('success', 'Qr Operators uploaded successfully');

            } catch (\Exception $e) {
                DB::rollBack();
                if (!empty($e->getMessage())) {
                    return redirect()->route('qrOperators.index')->with('error', $e->getMessage());
                }
                return redirect()->route('qrOperators.index')->with('error', 'An error occurred while uploading Qr Operators');
            }
        }
    }
}