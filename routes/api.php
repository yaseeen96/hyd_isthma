<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


/* version 1 */
Route::group(['prefix' => 'v1'], function () {
    /* auth */
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('verifyOtp', 'AuthController@verifyOtp');
        Route::post('verifyToken', 'AuthController@verifyToken');
    });
    /* protected routes */
    Route::middleware('auth:sanctum')->group(function () {
        Route::group(['prefix' => 'user'], function () {
            Route::get('getUserDetails', 'RegistrationController@index');
            Route::post('register', 'RegistrationController@register');
            Route::post('familyDetails', 'RegistrationController@updateFamilyDetails');
            Route::post('financialDetails', 'RegistrationController@updateFinancialDetails');
            Route::post('additionalDetails', 'RegistrationController@updateAdditionalDetails');
        });

        Route::post('logout', 'AuthController@logout');
        Route::post('deleteAccount', 'AuthController@deleteAccount');
    });
    Route::get('fetchUsers', 'DataFetchController@index');
    Route::get('getZones', 'AdminDataFetchController@getZoneNames');
});
