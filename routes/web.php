<?php

use App\Http\Controllers\CheckController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('', [GuestController::class, 'index'])->name('index');

Route::get('login', [GuestController::class, 'login'])->name('login');
Route::post('login', [GuestController::class, 'loginProcess']);


Route::middleware('auth')->group(callback: function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('customers', [CustomerController::class, 'index'])->name('customers');
    Route::get('settings', [SettingController::class, 'index'])->name('settings');
    Route::get('users', [UserController::class, 'index'])->name('users');
    Route::get('checks', [CheckController::class, 'index'])->name('checks');

});

Route::get('sys-logs-', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::get('logout', [GuestController::class, 'logout'])->name('logout');

