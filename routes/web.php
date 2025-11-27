<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class, 'showLogin']);

Route::get('/register', [AuthController::class, 'showRegister']);

Route::get('/login', [AuthController::class, 'showLogin']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout']);

Route::post('/api/auth/logout', [AuthController::class, 'logout']);

Route::get('/client/home', function () { return view('client.home');});

Route::get('/client/myqr', function () { return view('client.myqr');});

Route::get('/client/contact', function () {return view('client.contact');});

Route::get('/settings', function () {return view('settings');});
