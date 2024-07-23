<?php

namespace App\Http\Controllers;

use App\Helpers\PushNotificationHelper;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (auth()->user()->id != 1 && !auth()->user()->hasPermissionTo('View Notifications')){
            abort(403);
        }
        return view('admin.notifications.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.notifications.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $region = $request->input('region');
        $regionValue = $region . '_name';
        $regionCondition = $request->input($regionValue);
        $rules = [
            'region' => 'required',
            'title' => 'required',
            'message' => 'required'
        ];
        if($regionCondition === null ){
            $rules[$regionValue] =  'required';
        }
        $reg_status = $request->input('reg_status');
        $request->validate($rules);
        $title = $request->input('title');
        $message = $request->input('message');

        $query = Member::with('registration')->whereHas('registration', function ($q) use ($reg_status) {
             (!empty($reg_status) && $reg_status === 1) ? $q->where('confirm_arrival', $reg_status) : $q ;
        })->where($region . '_name' , $request->input($regionValue));
        if(!empty($request->input('gender'))) {
            $query->where('gender', $request->input('gender'));
        }
        $result = $query->get()->pluck('name')->toArray();
        $tokens = array_values(array_values($result));
        $is_send = PushNotificationHelper::sendNotification($tokens, $title, $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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