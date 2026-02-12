<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\PengurusMajlisController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\EventManagerController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin']);
Route::get('/register', [AuthController::class, 'showRegister']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/api/auth/logout', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| Client Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/client/home', [ClientController::class, 'home'])->name('client.home');
    Route::get('/client/myqr', [ClientController::class, 'myqr'])->name('client.myqr');
    Route::get('/client/contact', [ClientController::class, 'contact'])->name('client.contact');

    Route::post('/client/register-event', [ClientController::class, 'registerForEvent'])
        ->name('client.register.event');

    Route::post('/events/verify-password', [ClientController::class, 'verifyEventPassword'])
        ->name('events.verify.password');

    Route::get('/client/download-qr/{eventId}', [ClientController::class, 'downloadQr'])
        ->name('client.downloadQr');

    Route::post('/client/create-event', [ClientController::class, 'createEvent'])
        ->name('client.createEvent');

    Route::post('/client/event/{eventId}/deactivate', [ClientController::class, 'deactivateEvent'])
        ->name('client.deactivateQr');

    // QR scan (client self scan – optional)
    Route::get('/attendance/scan/{event_id}', [AttendanceController::class, 'scan'])
        ->name('attendance.scan');
});

/*
|--------------------------------------------------------------------------
| Settings
|--------------------------------------------------------------------------
*/
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

/*
|--------------------------------------------------------------------------
| Pengurus Majlis Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:pengurusMajlis', 'role:pengurusMajlis'])->group(function () {

    Route::get('/pengurusMajlis/home', [PengurusMajlisController::class, 'home'])
        ->name('pengurusMajlis.home');

    Route::get('/pengurusMajlis/events', [PengurusMajlisController::class, 'index'])
        ->name('pengurusMajlis.events.index');

    Route::get('/pengurusMajlis/events/create', [PengurusMajlisController::class, 'create'])
        ->name('pengurusMajlis.events.create');

    Route::post('/pengurusMajlis/events/create', [PengurusMajlisController::class, 'store'])
        ->name('pengurusMajlis.events.store');

    Route::get('/pengurusMajlis/events/{eventId}/edit', [PengurusMajlisController::class, 'edit'])
        ->name('pengurusMajlis.events.edit');

    Route::put('/pengurusMajlis/events/{eventId}', [PengurusMajlisController::class, 'update'])
        ->name('pengurusMajlis.events.update');

    Route::delete('/pengurusMajlis/events/{eventId}', [PengurusMajlisController::class, 'destroy'])
        ->name('pengurusMajlis.events.destroy');

    // Attendance page
    Route::get('/pengurusMajlis/attendance/{event_id}', [AttendanceController::class, 'show'])
        ->name('pengurusMajlis.attendance.show');

    // ROUTE SCANNER
    Route::post('/pengurusMajlis/attendance/record', [AttendanceController::class, 'record'])
        ->name('pengurusMajlis.attendance.record');

    // Profile Settings
    Route::get('/pengurusMajlis/profile', [ProfileController::class, 'edit'])
        ->name('pengurusMajlis.profile.edit');
    Route::post('/pengurusMajlis/profile', [ProfileController::class, 'update'])
        ->name('pengurusMajlis.profile.update');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {

    // Login admin
    Route::get('/', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

    // Protected routes for admin
    Route::middleware('admin')->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Logout
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

        // Event Managers
        Route::get('/event-managers', [EventManagerController::class, 'index'])
            ->name('admin.event-managers.index');

        Route::get('/event-managers/create', [EventManagerController::class, 'create'])
            ->name('admin.event-managers.create');

        Route::post('/event-managers', [EventManagerController::class, 'store'])
            ->name('admin.event-managers.store');
    });
});