<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\WasteReportController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\CleanupDriveLeaderController;

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
    Route::get('/user-cleanup-drives', [VolunteerController::class, 'drives'])->name('user-cleanup-drives');
    Route::get('/volunteer/apply', [VolunteerController::class, 'applyForm'])->name('volunteer.apply');
    Route::post('/volunteer/apply', [VolunteerController::class, 'apply'])->name('volunteer.apply.submit');
    Route::post('/cleanup-drives/{drive}/join', [VolunteerController::class, 'join'])->name('cleanup.join');
    Route::post('/cleanup-registrations/{registration}/cancel', [VolunteerController::class, 'cancel'])->name('cleanup.cancel');
    Route::post('/cleanup-registrations/{registration}/evidence', [VolunteerController::class, 'evidence'])->name('cleanup.evidence');
    Route::post('/cleanup-registrations/{registration}/verify-code', [VolunteerController::class, 'verifyCode'])->name('cleanup.verify-code');
    Route::get('/volunteer/my-activities', [VolunteerController::class, 'myActivities'])->name('volunteer.activities');
    Route::get('/volunteer/participation-history', [VolunteerController::class, 'history'])->name('volunteer.history');
    Route::get('/volunteer/cleanup-drives/{drive}/gallery', [VolunteerController::class, 'gallery'])->name('volunteer.gallery');
    Route::get('/volunteer/log', [VolunteerController::class, 'log'])->name('volunteer.log');

    Route::view('/user-messages', 'user-messages')->name('user-messages');
    Route::view('/user-profile', 'user-profile')->name('user-profile');
});


/*
|--------------------------------------------------------------------------
| Purok Leader pages
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::view('/purok-dash', 'purok-dash')->name('purok-dash');
    Route::view('/purok-reports', 'purok-reports')->name('purok-reports');
    Route::get('/purok-activities', [CleanupDriveLeaderController::class, 'index'])->name('purok-activities-db');
    Route::post('/purok-activities', [CleanupDriveLeaderController::class, 'store'])->name('purok-activities-db.store');
    Route::put('/purok-activities/{drive}', [CleanupDriveLeaderController::class, 'update'])->name('purok-activities-db.update');
    Route::post('/purok-activities/{drive}/cancel', [CleanupDriveLeaderController::class, 'cancel'])->name('purok-activities-db.cancel');
    Route::put('/purok-activities/participants/{registration}', [CleanupDriveLeaderController::class, 'updateParticipant'])->name('purok-activities-db.participant.update');
    Route::post('/purok-activities/{drive}/attendance-code', [CleanupDriveLeaderController::class, 'regenerate'])->name('purok-activities-db.code');
    Route::post('/purok-activities/registrations/{registration}/verify-photo', [CleanupDriveLeaderController::class, 'verifyPhoto'])->name('purok-activities-db.verify-photo');
    Route::get('/purok-activities/{drive}', [CleanupDriveLeaderController::class, 'show'])->name('purok-activities-db.show');
    Route::get('/purok-volunteers', [VolunteerController::class, 'reviewApplications'])->name('purok-volunteers');
    Route::post('/purok-volunteers/{application}/review', [VolunteerController::class, 'review'])->name('purok.volunteer-review');
    Route::get('/purok-volunteers/{application}/id/{side}', [VolunteerController::class, 'idDocument'])->name('purok.volunteer-id');
    Route::view('/purok-overview', 'purok-overview')->name('purok-overview');
    Route::view('/purok-messages', 'purok-messages')->name('purok-messages');
    Route::view('/purok-profile', 'purok-profile')->name('purok-profile');
});
