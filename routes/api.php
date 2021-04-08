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

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('user','App\Http\Controllers\UserController@getAuthenticatedUser');
    Route::get('getusers','App\Http\Controllers\UserController@show');
    Route::post('updateuser','App\Http\Controllers\UserController@update');
    Route::post('deleteuser','App\Http\Controllers\UserController@destroy');

    Route::post('createproduct','App\Http\Controllers\ProductController@store');
    Route::get('getproducts','App\Http\Controllers\ProductController@show');
    Route::post('updateproduct','App\Http\Controllers\ProductController@update');
    Route::post('deleteproduct','App\Http\Controllers\ProductController@destroy');

    Route::post('createorder','App\Http\Controllers\OrderController@store');
    Route::post('deleteorder','App\Http\Controllers\OrderController@destroy');
    Route::get('getorders','App\Http\Controllers\OrderController@show');
    Route::post('completedorder','App\Http\Controllers\OrderController@completedOrder');
});
