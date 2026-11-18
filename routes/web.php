<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\WasteReportController;

/*
|--------------------------------------------------------------------------
| Public pages
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('index');
});

Route::view('/index', 'index')->name('index');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.attempt');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1')->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot / reset password (these route names are required by Laravel's reset email)
Route::get('/forgot-password', [PasswordResetController::class, 'showForgot'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendLink'])->middleware('throttle:6,1')->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');


//when you logout n stuff
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Resident pages (must be logged in)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::view('/user-dash', 'user-dash')->name('user-dash');

    // Sending a report
    Route::get('/user-report', [WasteReportController::class, 'create'])->name('user-report');
    Route::post('/user-report', [WasteReportController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('user-report.store');

    Route::view('/user-my-reports', 'user-my-reports')->name('user-my-reports');
    Route::view('/user-collection-schedule', 'user-collection-schedule')->name('user-collection-schedule');
    Route::view('/user-cleanup-drives', 'user-cleanup-drives')->name('user-cleanup-drives');
    Route::view('/user-messages', 'user-messages')->name('user-messages');
    Route::view('/user-profile', 'user-profile')->name('user-profile');
});


/*
|--------------------------------------------------------------------------
| Purok Leader pages
|--------------------------------------------------------------------------
*/
    Route::view('/purok-dash', 'purok-dash')->name('purok-dash');
    Route::view('/purok-reports', 'purok-reports')->name('purok-reports');
    Route::view('/purok-activities', 'purok-activities')->name('purok-activities');
    Route::view('/purok-overview', 'purok-overview')->name('purok-overview');
    Route::view('/purok-volunteers', 'purok-volunteers')->name('purok-volunteers');
    Route::view('/purok-messages', 'purok-messages')->name('purok-messages');
    Route::view('/purok-profile', 'purok-profile')->name('purok-profile');
