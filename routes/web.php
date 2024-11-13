<?php

use App\Helpers\SmsHelper;
use App\Http\Controllers\Api\ProgramsController;
use App\Models\checkInOutEntires;
use App\Models\Member;
use App\Models\Notification;
use App\Models\QrBatchRegistration;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Auth::routes(['register' => false]);
Route::get('programs/listPrograms', [ProgramsController::class, 'listPrograms'])->name('programs');
Route::middleware('auth')->group(function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::post('/', 'DashboardController@index')->name('dashboard'); // for dashboard charts
    // Members
    Route::resource('members', 'MembersController');
    // Registration
    Route::resource('registrations', 'RegistrationController');
    // Notifications
    Route::resource('notifications', 'NotificationsController');
    // Permissions
    Route::resource('permissions', 'PermissionsController');
    // Programs
    Route::resource('sessiontheme', 'SessionThemeController');
    // Speakers
    Route::resource('programSpeakers', 'ProgramSpeakerController');
    // Programs
    Route::resource('programs', 'ProgramsController');
    // Users
    Route::resource('user', 'UserController');
    // Audio Processing
    Route::resource('audioProcessing', 'AudioProcessingController');
    // QR Code Operators
    Route::resource('qrOperators', 'QrCodeOperatorController');
    Route::post('bulkUpload', 'QrCodeOperatorController@bulkUpload')->name('qrOperators.bulkUpload');
    // Check In Out Places
    Route::resource('checkInOutPlaces', 'CheckInOutPlaceController');
    // Check In Out Entries
    Route::resource('checkInOutEntries', 'CheckInOutEntiresController');
    Route::get('positionReport', 'CheckInOutEntiresController@positionReport')->name('position-report');
    Route::get('totalCheckInOutReport', 'CheckInOutEntiresController@totalCheckInOutReport')->name('total-check-in-out-report');
    // Program Registration
    Route::resource('sessionRegistration', 'SessionRegistrationController');
    // QR Batch Registrations
    Route::resource('qrBatchRegistrations',  'QrBatchRegistrationController');
    route::post('bulkQrBatchRegistrations', 'QrBatchRegistrationController@bulkUpload')->name('qrBatchRegistrations.bulkUpload');
    // Feedback
    Route::resource('feedback', 'FeedbackController');
    // FAQ
    Route::resource('faq', controller: 'FaqController');
    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('familyDetailsReport', 'ReportsController@familyDetailsReport')->name('family-details-report');
        Route::get('paymentDetailsReport', 'ReportsController@paymentDetailsReport')->name('payment-details-report');
        Route::get('arrivalReport', 'ReportsController@arrivalReport')->name('arrival-report');
        Route::get('departureReport', 'ReportsController@departureReport')->name('departure-report');
        Route::get('commonDataReport', 'ReportsController@commonDataReport')->name('common-data-report');
        Route::get('purchaseDataReport', 'ReportsController@purchaseDataReport')->name('purchase-data-report');
        Route::get('sightSeeingDetailsReport', 'ReportsController@sightSeeingDetailsReport')->name('sight-seeing-details-report');
        Route::get('globalReport', 'ReportsController@globalReport')->name('global-report');
    });
    // filter helpers
    Route::post('getDivisions', 'DashboardController@getDivisions')->name('getDivisions');
    Route::post('getUnits', 'DashboardController@getUnits')->name('getUnits');
    Route::get('getStationNames', 'DashboardController@getStationNames')->name('get-station-names');
});
Route::get('syncrukundata', 'ReportsController@syncRukunData')->name('sync-rukun-data');
// Route::get('updateRukunAge', 'ReportsController@updateRukunAge')->name('update-rukun-age');
// Route::get('importQrBatchRegistrationData',  'QrBatchRegistrationController@import');
// temp routes
Route::prefix('delete')->group(function () {
    Route::get('account', 'DeleteAccountController@index')->name('delete-account');
    Route::post('account', 'DeleteAccountController@delete')->name('delete-account');
    Route::get('login', 'DeleteAccountController@login')->name('tmp-login');
    Route::post('otpVerify', 'DeleteAccountController@otpVerify')->name('tmp-otpVerify');
    Route::post('loginWithOtp', 'DeleteAccountController@loginWithOtp')->name('tmp-loginWithOtp');
    Route::get('logout', 'DeleteAccountController@logout')->name('tmp-logout');
});


Route::get('generate-tokens', function(){

    $members = Member::all();
    $tokens = [];

    foreach ($members as $member) {
        $member->tokens()->delete();
        $token = $member->createToken('user-token')->plainTextToken;

        array_push($tokens, $token);
    }

    $jsonContent = json_encode($tokens, JSON_PRETTY_PRINT);
     print_r($jsonContent);

});


/**
 * When you are a lazy developer you will always find short way's to get the work done.
 * 😊😴
*/


Route::get('testing', function () {
    $entires = checkInOutEntires::query()->where('batch_type', 'rukn')->get();
    foreach($entires as $entry) {
        $member = Member::where('user_number', $entry->batch_id)->first();
        if($member) {
            $entry->update([
                'phone_number' => $member->phone,
                'category' => 'Rukn'
            ]);
        }
    }
    $nonRuknEntires = checkInOutEntires::query()->where('batch_type', '!=', 'rukn')->get();
    foreach($nonRuknEntires as $entry) {
        $member = QrBatchRegistration::where('batch_id', $entry->batch_id)->first();
        if($member) {
            $entry->update([
                'phone_number' => $member->phone_number,
                'category' => $member->batch_type
            ]);
        }
    }

    // $id = 'dE6_IuEiQXuX6NgDy_cDKe:APA91bH7fhcp-5P5kSLIZIfDnerAbwINAqdlyUEkP5TLlieBK4tPFaRRtrG0n6Ax77SI4VkTJdVCyxN_VnxWRZ2y2dn-5NKNi4A74RNZyt_MPFNjZyTOG-a-WEg9s75o04dkUqS-_CDv';
    // $newId = 'eagsq256RfOqvXkq0zombk:APA91bHmg2TXNDDj4XsVkJAwRF8hVFJfaVVJglmddCcNaWKYpiNIrGJPoI9YpqR_KGkiOUnwINYuVIMn2925novL0GInWVl5-qQROSQKM8L46A7ItkhB9iA';
    // $notifications = Notification::whereJsonContains('valid_tokens', $id)->pluck('id')->toArray();
    // foreach($notifications as $notification) {
    //     $data = Notification::find($notification);
    //     if(in_array($id, $data->valid_tokens )) {
    //         $tokens = $data->valid_tokens;
    //         echo count($tokens);
    //         $tokens = array_diff($tokens, [$id]);
    //         array_push($tokens, $newId);
    //         $data->update(['valid_tokens' => $tokens]);
    //         if(in_array($id, $tokens )) {
    //             echo "yes";
    //         }
    //     }
    // }
    // $notification = Notification::find(43);
    // $users_ids = Member::whereIn('push_token', $notification->valid_tokens)->pluck('id')->toArray();

});