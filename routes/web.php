<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\DriversController;
use App\Http\Controllers\TripsController;




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



Route::get('/', [RegistrationController::class, 'index']);
Route::post('/login', [RegistrationController::class, 'login']);

Route::get('/web', [RegistrationController::class, 'web']);

Route::group(['middleware' => 'admin'], function () {

    Route::get('/profile/personal', [RegistrationController::class, 'profile']);
    Route::post('profile/password/update', [RegistrationController::class, 'updatePassword']);
    Route::get('logout', [RegistrationController::class, 'logout']);
    Route::get('dashboard', [DashboardController::class, 'index']);
    
    //Drivers
    Route::get('drivers', [DriversController::class, 'index']);
    Route::post('register/driver', [DriversController::class, 'create']);
    Route::get('get/driver/{id}', [DriversController::class, 'get']);
    Route::post('update/driver', [DriversController::class, 'update']);
    Route::get('delete/driver/{id}', [DriversController::class, 'delete']);
    // Route::get('approve/driver/{id}', [DriversController::class, 'approve']);
    Route::post('change/password', [DriversController::class, 'changePassword']);

    // Trips
    Route::get('trips', [TripsController::class, 'index']);
    Route::get('new/trip', [TripsController::class, 'new']);
    Route::post('trip/create', [TripsController::class, 'create']);
    Route::get('edit/trip', [TripsController::class, 'edit']);
    Route::get('delete/trip/{id}', [TripsController::class, 'delete']);
    // Route::get('all/trips/{from}/{to}', [TripsController::class, 'trips']);
    
    //Tracking
    // Route::get('live', [DriversController::class, 'liveIndex']);
    Route::get('live/location/{device_id}', [DriversController::class, 'live']);
    Route::get('all/live/location', [DriversController::class, 'allLocations']);

    Route::get('playback/index/{service_id}', [DriversController::class, 'playbackIndex']);
    Route::get('playback/history/{id}/{from}/{to}', [DriversController::class, 'playback']);


});
