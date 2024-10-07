<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SsoAuthController;

// Route untuk login SSO
Route::get('/login', [SsoAuthController::class, 'login'])->name('login');

// Callback dari SSO Service
Route::get('/callback', [SsoAuthController::class, 'callback'])->name('callback');

// Halaman Home setelah login
Route::get('/home', [SsoAuthController::class, 'home'])->name('home');

// Logout
Route::post('/logout', [SsoAuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return view('welcome');
});
