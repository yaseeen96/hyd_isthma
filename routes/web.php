<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/', 'DashboardController@index')->name('dashboard');
Route::resource('members', 'MembersController', ['only' => ['index']]);
Route::resource('registrations', 'RegistrationController', ['only' => ['index', 'show']]);

// temp routes
Route::prefix('delete')->group(function () {
    Route::get('account', 'DeleteAccountController@index')->name('delete-account');
    Route::post('account', 'DeleteAccountController@delete')->name('delete-account');
    Route::get('login', 'DeleteAccountController@login')->name('tmp-login');
    Route::post('otpVerify', 'DeleteAccountController@otpVerify')->name('tmp-otpVerify');
    Route::post('loginWithOtp', 'DeleteAccountController@loginWithOtp')->name('tmp-loginWithOtp');
    Route::get('logout', 'DeleteAccountController@logout')->name('tmp-logout');
});