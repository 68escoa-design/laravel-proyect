<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewController;

// Vue.js App (Main)
Route::get('/', function () {
    return view('vue-app');
})->name('home');

// Legacy Blade routes (keep for reference)
Route::get('/blade-login', [ViewController::class, 'login'])->name('blade-login');
Route::post('/blade-login', [ViewController::class, 'authenticate'])->name('blade-authenticate');
Route::get('/blade-dashboard', [ViewController::class, 'dashboard'])->name('blade-dashboard');
Route::post('/blade-logout', [ViewController::class, 'logout'])->name('blade-logout');
