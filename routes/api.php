<?php

use App\Http\Controllers\ApiController;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('auth:sanctum')->prefix('v1')->middleware(ApiAuthMiddleware::class)->group(function () {

    Route::post('id', [ApiController::class, 'id'])->name('National ID');
    Route::post('alienid', [ApiController::class, 'alienid'])->name('Alien ID');
    Route::post('dl', [ApiController::class, 'dl'])->name('Driving License');
    Route::post('krapin', [ApiController::class, 'krapin'])->name('KRA Pin');
    Route::post('business', [ApiController::class, 'business'])->name('Business');
    Route::post('plate', [ApiController::class, 'vehicleplate'])->name('Vehicle');
    //Route::any('phone', [ApiController::class, 'phone'])->name('phone');
    Route::get('banklist', [ApiController::class, 'banks'])->name('Banks');
    Route::post('bank', [ApiController::class, 'bank'])->name('Bank Account');
    Route::get('balance', [ApiController::class, 'balance'])->name('Balance');

});
