<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelperFunctions;
use App\Helpers\PushNotificationHelper;
use App\Models\Member;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Plank\Mediable\Facades\MediaUploader;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class NotificationsController extends Controller
{
    public function index(Request $request, DataTables $dataTables)
    {
        $user = User::find(auth()->user()->id);
        if (auth()->user()->id != 1 && !$user->hasPermissionTo('View Notifications')){
            abort(403);
        }
        if($request->ajax())
        {
            $query = Notification::query()->orderBy('id', 'desc');
            return $dataTables->eloquent($query)
                ->addColumn('image', function (Notification $notification) {
                    $imageSrc = !empty($notification->getMedia('notification_image')->first()) ? $notification->getMedia('notification_image')->first()->getUrl() : '/assets/img/no-image.png';
                    return '<img src="' . $imageSrc . '" width="80px" height="80px">';
                })->addColumn('document', function (Notification $notification) {
                    $docLink = !empty($notification->getMedia('notificaiton_doc')->first()) ? $notification->getMedia('notificaiton_doc')->first()->getUrl() : '';
                    return !empty($docLink) ? '<span class="badge badge-primary text-white"><a target="_blank" href="' . $docLink . '"><i class="fas fa-eye text-white"></i></a></span>' : '';
                })
                ->addColumn('notificaiton_criteria', function (Notification $notification) {
                    return '<p><b>Gender</b>: <span class="badge badge-primary">' . (ucfirst($notification->criteria['gender'] ) == null ? 'NA' : ucfirst($notification->criteria['gender'])) . '</span></p>'
                           .'<p><b>Region-</b>' . ucfirst($notification->criteria['region_type']) . ' - <span class="badge badge-secondary">' . $notification->criteria['region_value'] . '</span></p>';
                        //    .'<p><b>Registration Status</b>:<span class="badge badge-warning">' . ($notification->criteria['reg_status'] == 1 ? 'Confirmed' : 'Not Confirmed') . '</span></p>';
                })
                ->addColumn('action', function (Notification $notification) use($user) {
                    $link = $user->id == 1 || $user->hasPermissionTo('Delete Notifications') ?
                            '<span data-href="'.route('notifications.destroy', $notification->id).'" class="btn-purple notification-delete btn"><i class="fas fa-trash"></i></span>'
                            : "";
                    return $link;
                })->rawColumns(['image', 'document', 'notificaiton_criteria', 'action'] )->makeHidden(['criteria'])
                ->addIndexColumn()
                ->make(true);

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
        $regionKey = $region . '_name';
        $regionValue = $request->input($regionKey);
        // adding validation rules
        $rules = [
            // 'region' => 'required',
            'title' => 'required',
            'message' => 'required'
        ];
        $request->validate($rules);

        // collecting input data
        $title = $request->input('title');
        $message = $request->input('message');
        $ytUrl = $request->input('youtube_url');

        $criteria = array(
                'region_type' => $region,
                'region_value' => $regionValue,
        );
        // Query to fetch data with selected criteria
        $query = Member::with('registration')->whereHas('registration', function ($q) use ($request, $criteria) {
            // Registration Filters
            // hotel_required
            if( AppHelperFunctions::isSetAndNotEmpty($request->hotel_required_condition)  && AppHelperFunctions::isSetAndNotEmpty($request->hotel_required_value) ) {
                $q->where('hotel_required', $request->hotel_required_condition, $request->hotel_required_value);
                array_push($criteria, ['hotel_required' => ['condition' => $request->hotel_required_condition, 'value' => $request->hotel_required_value]]);
            }
            // confirm_arrival
            if( AppHelperFunctions::isSetAndNotEmpty($request->confirm_arrival_condition)  && AppHelperFunctions::isSetAndNotEmpty($request->confirm_arrival_value) ) {
                $q->where('confirm_arrival', $request->confirm_arrival_condition, $request->confirm_arrival_value);
                array_push($criteria, ['confirm_arrival' => ['condition' => $request->confirm_arrival_condition, 'value' => $request->confirm_arrival_value]]);
            }
            // arrival_date
            if( AppHelperFunctions::isSetAndNotEmpty($request->arrival_date_condition)  && AppHelperFunctions::isSetAndNotEmpty($request->arrival_date_value) ) {
                $q->whereDate(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(arrival_details, '$.datetime'))"), $request->arrival_date_condition, $request->arrival_date_value);
                array_push($criteria, ['arrival_date' => ['condition' => $request->arrival_date_condition, 'value' => $request->arrival_date_value]]);
            }
            // departure_date
            if( AppHelperFunctions::isSetAndNotEmpty($request->departure_date_condition)  && AppHelperFunctions::isSetAndNotEmpty($request->departure_date_value) ) {
                $q->whereDate(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(departure_details, '$.datetime'))"), $request->departure_date_condition, $request->departure_date_value);
                array_push($criteria, ['departure_date' => ['condition' => $request->departure_date_condition, 'value' => $request->departure_date_value]]);
            }
            // arrival_mode
            if( AppHelperFunctions::isSetAndNotEmpty($request->arrival_mode_condition)  && AppHelperFunctions::isSetAndNotEmpty($request->arrival_mode_value) ) {
                $q->where('arrival_details->mode', $request->arrival_mode_condition, $request->arrival_mode_value);
                array_push($criteria, ['arrival_mode' => ['condition' => $request->arrival_mode_condition, 'value' => $request->arrival_mode_value]]);
            }
            // departure_mode
            if( AppHelperFunctions::isSetAndNotEmpty($request->departure_mode_condition)  && AppHelperFunctions::isSetAndNotEmpty($request->departure_mode_value) ) {
                $q->where('departure_details->mode', $request->departure_mode_condition, $request->departure_mode_value);
                array_push($criteria, ['departure_mode' => ['condition' => $request->departure_mode_condition, 'value' => $request->departure_mode_value]]);
            }
            // arrival_mode_identifier
            if( AppHelperFunctions::isSetAndNotEmpty($request->arrival_mode_identifier_condition)  && AppHelperFunctions::isSetAndNotEmpty($request->arrival_mode_identifier_value) ) {
                $q->where('arrival_details->mode_identifier', $request->arrival_mode_identifier_condition, $request->arrival_mode_identifier_value);
                array_push($criteria, ['arrival_mode_identifier' => ['condition' => $request->arrival_mode_identifier_condition, 'value' => $request->arrival_mode_identifier_value]]);
            }
            // departure_mode_identifier
            if( AppHelperFunctions::isSetAndNotEmpty($request->departure_mode_identifier_condition)  && AppHelperFunctions::isSetAndNotEmpty($request->departure_mode_identifier_value) ) {
                $q->where('departure_details->mode_identifier', $request->departure_mode_identifier_condition, $request->departure_mode_identifier_value);
                array_push($criteria, ['departure_mode_identifier' => ['condition' => $request->departure_mode_identifier_condition, 'value' => $request->departure_mode_identifier_value]]);
            }
            // sight_seeing_required
            if( AppHelperFunctions::isSetAndNotEmpty($request->sight_seeing_required_condition)  && AppHelperFunctions::isSetAndNotEmpty($request->sight_seeing_required_value) ) {
                $q->where('sight_seeing->required', $request->sight_seeing_required_condition, $request->sight_seeing_required_value);
                array_push($criteria, ['sight_seeing_required' => ['condition' => $request->sight_seeing_required_condition, 'value' => $request->sight_seeing_required_value]]);
            }
            // need_attendant
            if( AppHelperFunctions::isSetAndNotEmpty($request->need_attendant_condition)  && AppHelperFunctions::isSetAndNotEmpty($request->need_attendant_value) ) {
                $q->where('special_considerations->need_attendant', $request->need_attendant_condition, $request->need_attendant_value);
                array_push($criteria, ['need_attendant' => ['condition' => $request->need_attendant_condition, 'value' => $request->need_attendant_value]]);
            }
            // cot_or_bed
            if( AppHelperFunctions::isSetAndNotEmpty($request->cot_or_bed_condition)  && AppHelperFunctions::isSetAndNotEmpty($request->cot_or_bed_value) ) {
                $q->where('special_considerations->cot_or_bed', $request->cot_or_bed_condition, $request->cot_or_bed_value);
                array_push($criteria, ['cot_or_bed' => ['condition' => $request->cot_or_bed_condition, 'value' => $request->cot_or_bed_value]]);
            }

        })->where(function ($q) use ($regionValue, $region, $request, $criteria) {
            // Member Table Filters
            !empty($regionValue) ? $q->where($region . '_name' , $regionValue) : $q;
            // gender
            if( AppHelperFunctions::isSetAndNotEmpty($request->gender_condition)  && AppHelperFunctions::isSetAndNotEmpty($request->gender_value) ) {
                $q->where('gender', $request->gender_condition, $request->gender_value);
                array_push($criteria, ['gender' => ['condition' => $request->gender_condition, 'value' => $request->gender_value]]);
            }
            // year_of_rukniyat
            if( AppHelperFunctions::isSetAndNotEmpty($request->year_of_rukniyat_condition)  && AppHelperFunctions::isSetAndNotEmpty($request->year_of_rukniyat_value) ) {
                $q->where('year_of_rukniyat', $request->year_of_rukniyat_condition, $request->year_of_rukniyat_value);
                array_push($criteria, ['year_of_rukniyat' => ['condition' => $request->year_of_rukniyat_condition, 'value' => $request->year_of_rukniyat_value]]);
            }
            // dob
            if( AppHelperFunctions::isSetAndNotEmpty($request->dob_condition)  && AppHelperFunctions::isSetAndNotEmpty($request->dob_value) ) {
                $q->date('dob', $request->dob_condition, $request->dob_value);
                array_push($criteria, ['dob' => ['condition' => $request->dob_condition, 'value' => $request->dob_value]]);
            }
            // email
            if( AppHelperFunctions::isSetAndNotEmpty($request->email_condition)  && AppHelperFunctions::isSetAndNotEmpty($request->email_value) ) {
                $q->where('email', $request->email_condition, $request->email_value);
                array_push($criteria, ['email' => ['condition' => $request->email_condition, 'value' => $request->email_value]]);
            }
        })->where('push_token', '!=', null)->where('push_token', '!=', 'none');


        $result = $query->get()->pluck('push_token')->toArray();
        $tokens = array_values(array_values($result));
        if (empty($tokens)) {
            return back()->with(['error' => 'No Users with registered tokens found with provided condition']);
        }
        // preparing
        $notificationData = array(
            'title' => $title,
            'message' => $message,
            'criteria' => $criteria,
            'youtube_url' => $ytUrl
            );
        // Storing notification
        $notification = Notification::create($notificationData);
        // Uploading image to server
        $imgUrl = '';
        if(!empty($request->file('notification_image'))) {
            $media = MediaUploader::fromSource($request->file('notification_image'))->toDestination('public', 'images/notification_image')->useFilename(Str::uuid())->upload();
            $notification->attachMedia($media, ['notification_image']);
            $imgUrl = $notification->getMedia('notification_image')->first()->getUrl();
        }
        // upload document to server
        if(!empty($request->file('notificaiton_doc'))) {
            $media = MediaUploader::fromSource($request->file('notificaiton_doc'))->toDestination('public', 'images/notificaiton_doc')->useFilename(Str::uuid())->upload();
            $notification->attachMedia($media, ['notificaiton_doc']);
        }
        // Sending Push Notification.
        $notificationData = PushNotificationHelper::sendNotification([
            'tokens' => $tokens,
            'title' => $title,
            'message' => $message,
            'imgUrl' => $imgUrl,
            'ytUrl' => $ytUrl,
            'id' => $notification->id
        ]);
        $notification->update($notificationData);
        return back()->with('success', 'Notification Send Successfully');
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
        $user = User::find(auth()->user()->id);
        if ($user->id != 1 && !$user->hasPermissionTo('Delete Notifications')){
            abort(403);
        }
        $notification = Notification::find($id);
        $notification->getMedia('notification_image')->each->delete();
        $notification->getMedia('notificaiton_doc')->each->delete();
        $notification->delete();
        return response()->json([
            'message' => 'Notifications Updated Successfully',
            'status' => 200
        ], 200);
    }
}
