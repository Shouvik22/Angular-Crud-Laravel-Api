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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('test_store','API\ApiController@store');
Route::post('signup','API\ApiController@signup');
Route::post('token/generate-token','API\ApiController@generate_token');

Route::get('fetch-list','API\ApiController@fetch_list');

Route::post('add-list','API\ApiController@add_list');

Route::get('edit-list/{id}','API\ApiController@edit_list');
Route::post('update-list/{id}','API\ApiController@update_list');
Route::get('delete-list/{id}','API\ApiController@delete_list');