<?php

use App\Http\Controllers\Api\EwsDeviceController;
use App\Http\Controllers\Api\EwsDeviceMeasurementController;
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

Route::get('ews-devices', [EwsDeviceController::class, 'index']);
Route::get('ews-device-measurements', [EwsDeviceController::class, 'index']);

Route::post('ews-device', [EwsDeviceController::class, 'store']);
Route::get('ews-device/{id}', [EwsDeviceController::class, 'show']);
Route::post('ews-device/{id}', [EwsDeviceController::class, 'update']);
Route::delete('ews-device/{id}', [EwsDeviceController::class, 'destroy']);

Route::get('ews-device-measurement', [EwsDeviceMeasurementController::class, 'store']);
Route::get('ews-device-measurement/{id}', [EwsDeviceMeasurementController::class, 'show']);
Route::post('ews-device-measurement/{id}', [EwsDeviceMeasurementController::class, 'update']);
Route::delete('ews-device-measurement/{id}', [EwsDeviceMeasurementController::class, 'destroy']);
