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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::get('logout', 'App\Http\Controllers\AuthController@logout');
    Route::get('acl-permission', 'App\Http\Controllers\AuthController@aclPermissionIndex');
    Route::post('acl-permission', 'App\Http\Controllers\AuthController@aclPermissionStore');
    Route::post('acl-permission', 'App\Http\Controllers\AuthController@aclPermissionSelected');
    Route::post('medicine', 'App\Http\Controllers\MedicineController@store');
    Route::post('medicine-incoming', 'App\Http\Controllers\MedicineController@incoming');
    Route::post('medicine-outgoing', 'App\Http\Controllers\MedicineController@outgoing');
    Route::get('medicine-outgoing', 'App\Http\Controllers\MedicineController@outgoingList');
});

Route::post('/register', 'App\Http\Controllers\RegisterController@store');
Route::post('/klinik', 'App\Http\Controllers\ClinicController@store');
Route::post('/unit', 'App\Http\Controllers\UnitController@store');
