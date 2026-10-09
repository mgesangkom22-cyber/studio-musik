<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RfidApiController;

/*
|--------------------------------------------------------------------------
| API Routes for ESP32 & RFID Integration
|--------------------------------------------------------------------------
*/

Route::prefix('rfid')->group(function () {
    Route::post('/register', [RfidApiController::class, 'registerScan']);
    Route::post('/scan', [RfidApiController::class, 'memberScan']);
    Route::get('/latest-scanned', [RfidApiController::class, 'getLatestScanned']);
    Route::post('/clear-latest', [RfidApiController::class, 'clearLatestScanned']);
});

// Direct RFID Fallbacks
Route::post('/register', [RfidApiController::class, 'registerScan']);
Route::post('/scan', [RfidApiController::class, 'memberScan']);

