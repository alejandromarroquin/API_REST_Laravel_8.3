<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('createuser','App\Http\Controllers\UserController@store');
Route::post('login', 'App\Http\Controllers\UserController@authenticate');
Route::post('updatepassword','App\Http\Controllers\UserController@updatePassword');

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('user','App\Http\Controllers\UserController@getAuthenticatedUser');
    Route::post('updateuser','App\Http\Controllers\UserController@update');
    Route::post('deleteuser','App\Http\Controllers\UserController@destroy');
});
