<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelperFunctions;
use App\Models\checkInOutEntires;
use App\Models\QrBatchRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;

class QrBatchRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, DataTables $datatables)
    {
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('View BatchesManagement')){
            abort(403);
        }
        if($request->ajax())
        {
            $query = QrBatchRegistration::query()->where(function($query) use($request){
                if(!empty($request->zone_name)) {
                    $query->where('zone_name', $request->zone_name);
                }
                if(!empty($request->division_name)) {
                    $query->where('division_name', $request->division_name);
                }
                if(!empty($request->unit_name)) {
                    $query->where('unit_name', $request->unit_name);
                }
            })->orderBy('batch_id', 'asc');
            return $datatables->eloquent($query)
                ->addColumn('action', function (QrBatchRegistration $batch) use ($user) {
                     return ($user->id == 1 || $user->hasPermissionTo('Edit BatchesManagement')) ?
                        '<a href="' . route('qrBatchRegistrations.edit', $batch->id) . '" class="btn-purple btn mr-1" ><i class="fas fa-edit"></i></a>'
                        : "";
                    // if(empty($batch->full_name)) {

                    // }
                    // return AppHelperFunctions::getGreenBadge('Assigned');
                })
                ->rawColumns(['action'])
                ->addIndexColumn()->make(true);

        }
        return view('admin.qrbatcheregistrations.list');
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(QrBatchRegistration $qrBatchRegistration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('Edit BatchesManagement')){
            abort(403);
        }
        return view('admin.qrbatcheregistrations.form')->with([
            'batch' => QrBatchRegistration::find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QrBatchRegistration $qrBatchRegistration)
    {
        $data = $request->validate([
            'full_name' => 'required',
            'gender' => 'required',
            'phone_number' => 'required',
            'zone_name' => 'required',
            'division_name' => 'required',
            'unit_name' => 'required',
        ]);
        $data['email'] = $request->email;
        $qrBatchScannEntires = checkInOutEntires::where('batch_id', $qrBatchRegistration->batch_id)->where('batch_type', 'nonRukn')->get();
        if($qrBatchScannEntires->count() > 0) {
            foreach($qrBatchScannEntires as $qrBatchScannEntire) {
                $qrBatchScannEntire->update([
                    'name' => $data['full_name'],
                    'email' => $data['email'],
                    'phone_number' => $data['phone_number'],
                    'zone_name' => $data['zone_name'],
                    'division_name' => $data['division_name'],
                    'unit_name' => $data['unit_name'],
                ]);
            }
        }
        $qrBatchRegistration->update($data);
        return redirect()->back()->with('success', 'Qr Batch Registrations updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QrBatchRegistration $qrBatchRegistration)
    {
        //
    }

    public function import(Request $request)
    {
        $row = 1;
        if (($handle = fopen(env('DATA_FILE'), "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if($row > 1) {
                    $qrBatchRegistration = new QrBatchRegistration();
                    if(QrBatchRegistration::where('batch_id', $data[1])->exists()) {
                        continue;
                    }
                    $qrBatchRegistration->batch_id = $data[1];
                    $qrBatchRegistration->gender = $data[2];
                    $qrBatchRegistration->batch_type = $data[3];
                    $qrBatchRegistration->save();
                }
                $row++;
            }
            fclose($handle);
        }
    }

    public function bulkUpload(Request $request) {

        $request->validate([
            'qrbatches_bulkupload' => 'required'
        ]);
        if($request->file('qrbatches_bulkupload')->getClientOriginalExtension() != 'csv'){
            return redirect()->route('qrBatchRegistrations.index')->with('error', 'Only CSV files are allowed');
        }
        if ($request->hasFile('qrbatches_bulkupload')) {
            $csv = Reader::createFromPath($request->file('qrbatches_bulkupload')->getPathname(), 'r');
            $csv->setHeaderOffset(0);
            $records = $csv->getRecords();
            $headers = $csv->getHeader();
            DB::beginTransaction();
            if (empty($records)) {
                return redirect()->route('qrBatchRegistrations.index')->with('error', 'No records found in the uploaded file');
            }
            try {
                foreach ($records as $offset => $record) {
                    if (empty($record['batch_id']) && empty($record['batch_type']) && empty($record['gender']) ) {
                        throw new \Exception('Batch Id, Batch type & gender are required');
                    }
                    $qrbatchReg = QrBatchRegistration::where('batch_id', $record['batch_id'])->first();
                    if (empty($qrbatchReg)) {
                        throw new \Exception('Batch Id does not exist');
                    }
                    $qrbatchReg->update([
                        'full_name' => $record['full_name'],
                        'email' => $record['email'],
                        'phone_number' => $record['phone_number'],
                        'zone_name' => $record['zone_name'],
                        'division_name' => $record['division_name'],
                        'unit_name' => $record['unit_name'],
                    ]);
                    $qrBatchScannEntires = checkInOutEntires::where('batch_id', $record['batch_id'])->where('batch_type', 'nonRukn')->get();
                    if($qrBatchScannEntires->count() > 0) {
                        foreach($qrBatchScannEntires as $qrBatchScannEntire) {
                            $qrBatchScannEntire->update([
                                'name' => $record['full_name'],
                                'email' => $record['email'],
                                'phone_number' => $record['phone_number'],
                                'zone_name' => $record['zone_name'],
                                'division_name' => $record['division_name'],
                                'unit_name' => $record['unit_name'],
                            ]);
                        }
                    }
                    DB::commit();
                    return redirect()->route('qrBatchRegistrations.index')->with('success', 'Qr batch registrations uploaded successfully');
                }
            }catch (\Exception $e) {
                DB::rollBack();
                if (!empty($e->getMessage())) {
                    return redirect()->route('qrBatchRegistrations.index')->with('error', $e->getMessage());
                }
                return redirect()->route('qrBatchRegistrations.index')->with('error', 'An error occurred while uploading Qr Operators');
            }
        }
    }
}