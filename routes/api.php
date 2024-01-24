<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\NotificationController;
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

Route::post('/login', [RegistrationController::class, 'login']);
Route::get('/no-token', [RegistrationController::class, 'noTokenFound']);

Route::middleware('auth:api')->group(function () {
    Route::get('/logout', [RegistrationController::class, 'logout']);

    Route::post('/save/fcm', [RegistrationController::class, 'fcm']);
    Route::get('/status', [RegistrationController::class, 'status']);

    Route::get('/profile', [RegistrationController::class, 'profile']);
    Route::post('/update/profile', [RegistrationController::class, 'updateProfile']);
    Route::post('/change/password', [RegistrationController::class, 'changePassword']);


    Route::get('/all/trips', [TripController::class, 'all']);
    Route::get('/completed/trips', [TripController::class, 'completed']);
    Route::get('/ongoing/trip', [TripController::class, 'ongoing']);
    Route::post('/start/trip', [TripController::class, 'start']);
    Route::post('/pickup/trip', [TripController::class, 'pickup']);
    Route::post('/end/trip', [TripController::class, 'end']);
    Route::post('/stop/trip', [TripController::class, 'stop']);
    Route::post('/exit/stop', [TripController::class, 'exit']);

    //Notifications
    Route::get('/all/notifications', [NotificationController::class, 'index']);
    Route::get('/seen/notifications', [NotificationController::class, 'seenNotification']);
});
