<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/client/home', function () {
    return view('client.home');
});

Route::get('/client/myqr', function () {
    return view('client.myqr');
});

Route::get('/client/contact', function () {
    return view('client.contact');
});
