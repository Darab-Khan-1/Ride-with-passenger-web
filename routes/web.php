<?php

use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\DriversController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LinksController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TripsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingsController;

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
Route::get('/live/track/events/{slug}', [DriversController::class, 'groupShare']);
Route::get('/live/track/trip/{slug}/{id}', [DriversController::class, 'liveGroup']);

Route::get('/web', [RegistrationController::class, 'web']);
Route::get('live/share/location/{slug}', [DriversController::class, 'liveshare']);
Route::group(['middleware' => 'admin'], function () {

    Route::get('/advertisement', [SettingsController::class, 'advertisementIndex']);
    Route::post('/advertisement/save', [SettingsController::class, 'saveAdvertisement']);

    Route::post('/save/token', [RegistrationController::class, 'saveToken'])->name('save.token');
    Route::get('/profile/personal', [RegistrationController::class, 'profile']);
    Route::post('profile/password/update', [RegistrationController::class, 'updatePassword']);
    Route::get('logout', [RegistrationController::class, 'logout']);
    Route::get('dashboard', [DashboardController::class, 'index']);

    Route::post('sync/events', [TripsController::class, 'sync']);
    // Langauge
    Route::get('change/lang', [DashboardController::class, 'lang_change'])->name('User.LangChange');

    //Drivers
    Route::get('drivers', [DriversController::class, 'index']);
    Route::post('register/driver', [DriversController::class, 'create']);
    Route::get('get/driver/{id}', [DriversController::class, 'get']);
    Route::post('update/driver', [DriversController::class, 'update']);
    Route::get('delete/driver/{id}', [DriversController::class, 'delete']);
    // Route::get('approve/driver/{id}', [DriversController::class, 'approve']);
    Route::post('change/password', [DriversController::class, 'changePassword']);

    
    // Trips
    Route::get('trips', [TripsController::class, 'available']);
    Route::get('available/trips/{from}/{to}/{customer}', [TripsController::class, 'availableTrips']);
    Route::get('active/trips/{status?}', [TripsController::class, 'active']);
    Route::get('completed/trips', [TripsController::class, 'completed']);
    Route::get('new/trip', [TripsController::class, 'new']);
    Route::post('trip/create', [TripsController::class, 'create']);
    Route::get('edit/trip/{id}', [TripsController::class, 'edit']);
    Route::get('duplicate/trips/{id}', [TripsController::class, 'duplicate']);
    Route::post('trip/update', [TripsController::class, 'update']);
    Route::post('active/trip/update', [TripsController::class, 'activeUpdate']);
    Route::get('delete/trip/{id}', [TripsController::class, 'delete']);

    //Links
    Route::get('links', [LinksController::class, 'index']);
    Route::get('/new/link', [LinksController::class, 'add']);
    Route::get('/edit/link/{id}', [LinksController::class, 'edit']);
    Route::post('/register/link', [LinksController::class, 'create']);
    Route::post('/update/link', [LinksController::class, 'update']);
    Route::get('/delete/link/{id}', [LinksController::class, 'delete']);
    Route::get('/change/link/status/{id}', [LinksController::class, 'status']);
    

    Route::get('live/location/{device_id}', [DriversController::class, 'live']);
    Route::get('all/live/location', [DriversController::class, 'allLocations']);
    

    Route::get('playback/index/{service_id}', [DriversController::class, 'playbackIndex']);
    Route::get('playback/history/{id}/{from}/{to}', [DriversController::class, 'playback']);

    //Roles
    Route::get('/give/all/permissions', [RoleController::class, 'assignAllPermissionsToUser']);
    Route::get('create/permissions', [RoleController::class, 'createPermission']);
    Route::get('roles', [RoleController::class, 'index']);
    Route::post('register/role', [RoleController::class, 'create']);
    Route::get('get/role/{id}', [RoleController::class, 'get']);
    Route::get('delete/role/{id}', [RoleController::class, 'delete']);
    Route::post('update/role', [RoleController::class, 'update']);

    //Employees
    Route::get('employees', [EmployeeController::class, 'index']);
    Route::post('register/employee', [EmployeeController::class, 'create']);
    Route::get('get/employee/{id}', [EmployeeController::class, 'get']);
    Route::get('delete/employee/{id}', [EmployeeController::class, 'delete']);
    Route::post('update/employee', [EmployeeController::class, 'update']);

    //Google Calender
    Route::get('/google/auth', [TripsController::class, 'showAuthorizationForm'])->name('google.auth');
    Route::get('/google/auth/callback', [TripsController::class, 'handleAuthorizationCallback'])->name('google.auth.callback');
    Route::get('/google/events', [TripsController::class, 'showEvents'])->name('google.events.index');
    Route::post('/google/events/add', [TripsController::class, 'addEvent'])->name('google.events.add');

    //Notification
    Route::post('/custom/notification/{id}', [DriversController::class, 'customNotification'])->name('send.notification');
    Route::get('/all/notifications', [NotificationController::class, 'index'])->name('all.notification');



    // Company 
    Route::get('/customer', [CustomerController::class,'customerInfo']);
    Route::get('/create/customer', [CustomerController::class,'createcustomer']);
    Route::post('/store/customer',[CustomerController::class ,'create']);
    Route::get('/edit/customer/{id}',[CustomerController::class ,'edit']);
    Route::get('/delete/customer/{id}', [CustomerController::class,'delete']);
    Route::post('update/customer', [CustomerController::class, 'update']);
    Route::post('change/customer/password', [CustomerController::class, 'change_password']);

    // Route::post('/google/events/update/{eventId}', [TripsController::class, 'updateEvent'])->name('google.events.update');
    // Route::delete('/google/events/delete/{eventId}', [TripsController::class, 'deleteEvent'])->name('google.events.delete');
});
