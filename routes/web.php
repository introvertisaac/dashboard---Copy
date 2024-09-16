<?php

use App\Http\Controllers\CheckController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerTransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Livewire\PasswordReset;
use App\Livewire\PasswordResetForm;
use Illuminate\Support\Facades\Route;


Route::get('', [GuestController::class, 'index'])->name('index');

Route::get('login', [GuestController::class, 'login'])->name('login');
Route::post('login', [GuestController::class, 'loginProcess']);

Route::get('password/reset', PasswordReset::class)->name('password.request');
Route::get('/password/reset/{token}/{username}', PasswordResetForm::class)->name('password.reset');

Route::middleware('auth')->group(callback: function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/{customer_uuid}', [DashboardController::class, 'index'])->name('dashboard.customer');
    Route::get('customers', [CustomerController::class, 'index'])->name('customers');
    Route::get('settings', [SettingController::class, 'index'])->name('settings');
    Route::get('users', [UserController::class, 'index'])->name('users');
    Route::get('checks', [CheckController::class, 'index'])->name('checks');
    Route::get('customer-transactions', [CustomerTransactionController::class, 'index'])->name('customer.transactions');

});

Route::get('sys-logs-', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::get('logout', [GuestController::class, 'logout'])->name('logout');

