<?php

use App\Helpers\SmsHelper;
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
Route::middleware('auth')->group(function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');
    // Members
    Route::resource('members', 'MembersController');
    // Registration
    Route::resource('registrations', 'RegistrationController', ['only' => ['index', 'show']]);
    // Notifications
    Route::resource('notifications', 'NotificationsController');
    // Permissions
    Route::resource('permissions', 'PermissionsController');
    // Users
    Route::resource('user', 'UserController');
    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('familyDetailsReport', 'ReportsController@familyDetailsReport')->name('family-details-report');
        Route::get('paymentDetailsReport', 'ReportsController@paymentDetailsReport')->name('payment-details-report');
        Route::get('arrivalReport', 'ReportsController@arrivalReport')->name('arrival-report');
        Route::get('departureReport', 'ReportsController@departureReport')->name('departure-report');
        Route::get('commonDataReport', 'ReportsController@commonDataReport')->name('common-data-report');
        Route::get('purchaseDataReport', 'ReportsController@purchaseDataReport')->name('purchase-data-report');
        Route::get('sightSeeingDetailsReport', 'ReportsController@sightSeeingDetailsReport')->name('sight-seeing-details-report');
    });
    // filter helpers
    Route::get('getDivisions', 'DashboardController@getDivisions')->name('getDivisions');
    Route::get('getUnits', 'DashboardController@getUnits')->name('getUnits');
});
// temp routes
Route::prefix('delete')->group(function () {
    Route::get('account', 'DeleteAccountController@index')->name('delete-account');
    Route::post('account', 'DeleteAccountController@delete')->name('delete-account');
    Route::get('login', 'DeleteAccountController@login')->name('tmp-login');
    Route::post('otpVerify', 'DeleteAccountController@otpVerify')->name('tmp-otpVerify');
    Route::post('loginWithOtp', 'DeleteAccountController@loginWithOtp')->name('tmp-loginWithOtp');
    Route::get('logout', 'DeleteAccountController@logout')->name('tmp-logout');
});