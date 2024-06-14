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