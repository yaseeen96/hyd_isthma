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
    });
    /* protected routes */
    Route::middleware('auth:sanctum')->group(function () {
        Route::group(['prefix' => 'user'], function () {
            Route::get('getUserDetails', 'UserController@index');
            Route::post('UpdateUserDetails', 'UserController@update');
        });
    });
    Route::get('fetchUsers', 'DataFetchController@index');
});