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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('signup', 'Api\AuthController@signup');

Route::post('login', 'Api\AuthController@login');


Route::group(['middleware' => 'auth:api'], function() 
{
    Route::get('logout', 'Api\AuthController@logout');
    Route::post('notes', 'Api\AuthController@storeNote');
    Route::get('user', 'Api\AuthController@user');
    Route::get('notes', 'Api\AuthController@getNotes');
    Route::get('notes/{id}', 'Api\AuthController@getNote');
    Route::put('notes/{id}', 'Api\AuthController@updateNote');
    Route::delete('notes/{id}', 'Api\AuthController@destroyNote');
});

Route::get('unauthenticated', 'Api\AuthController@unauthenticated')->name('unauthenticated');