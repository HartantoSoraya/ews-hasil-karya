<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\EwsDeviceController;
use App\Http\Controllers\Api\EwsDeviceMeasurementController;
use App\Http\Controllers\Api\NotificationRecepientController;
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

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me'])->name('me');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('ews-devices', [EwsDeviceController::class, 'index']);

    Route::post('ews-device', [EwsDeviceController::class, 'store']);
    Route::get('ews-device/{id}', [EwsDeviceController::class, 'show']);
    Route::post('ews-device/{id}', [EwsDeviceController::class, 'update']);
    Route::delete('ews-device/{id}', [EwsDeviceController::class, 'destroy']);

    Route::get('ews-device-measurements', [EwsDeviceMeasurementController::class, 'index']);

    Route::get('ews-device-measurements/chart', [EwsDeviceMeasurementController::class, 'chart']);
    Route::post('ews-device-measurement', [EwsDeviceMeasurementController::class, 'store']);
    Route::get('ews-device-measurement/{id}', [EwsDeviceMeasurementController::class, 'show']);
    Route::post('ews-device-measurement/{id}', [EwsDeviceMeasurementController::class, 'update']);
    Route::delete('ews-device-measurement/{id}', [EwsDeviceMeasurementController::class, 'destroy']);

    Route::get('clients', [ClientController::class, 'index']);

    Route::post('client', [ClientController::class, 'store']);
    Route::get('client/{id}', [ClientController::class, 'show']);
    Route::post('client/{id}', [ClientController::class, 'update']);
    Route::post('client/active/{id}', [ClientController::class, 'updateActiveStatus']);
    Route::delete('client/{id}', [ClientController::class, 'destroy']);

    Route::get('notification-recepients', [NotificationRecepientController::class, 'index']);

    Route::post('notification-recepient', [NotificationRecepientController::class, 'store']);
    Route::get('notification-recepient/{id}', [NotificationRecepientController::class, 'show']);
    Route::post('notification-recepient/{id}', [NotificationRecepientController::class, 'update']);
    Route::post('notification-recepient/active/{id}', [NotificationRecepientController::class, 'updateActiveStatus']);
    Route::delete('notification-recepient/{id}', [NotificationRecepientController::class, 'destroy']);
});

Route::get('ews-device-measurement', [EwsDeviceMeasurementController::class, 'store']);
