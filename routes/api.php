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
Route::prefix('v1')->middleware(ApiAuthMiddleware::class)->group(function () {

    Route::post('id', [ApiController::class, 'id'])->name('id');
    Route::post('alienid', [ApiController::class, 'alienid'])->name('alienid');
    Route::post('business', [ApiController::class, 'business'])->name('business');
    Route::any('balance', [ApiController::class, 'balance'])->name('balance');
    Route::any('krapin', [ApiController::class, 'krapin'])->name('krapin');
    Route::any('dl', [ApiController::class, 'dl'])->name('dl');
    Route::any('plate', [ApiController::class, 'vehicleplate'])->name('vehicleplate');
    Route::any('phone', [ApiController::class, 'phone'])->name('phone');
    Route::any('banklist', [ApiController::class, 'banks'])->name('banklist');
    Route::any('bank', [ApiController::class, 'bank'])->name('banks');

});
