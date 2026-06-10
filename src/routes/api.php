<?php

use App\Http\Controllers\Api\AspirasiSubmissionApiController;
use App\Http\Controllers\Api\DashboardStatisticApiController;
use App\Http\Controllers\Api\KategoriAspirasiApiController;
use App\Http\Controllers\Api\TrackingApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/tracking/{ticket_number}', [TrackingApiController::class, 'show'])
        ->middleware('throttle:30,1')
        ->name('api.tracking.show');

    Route::get('/kategori-aspirasi', [KategoriAspirasiApiController::class, 'index'])
        ->middleware('throttle:60,1')
        ->name('api.kategori-aspirasi.index');

    Route::get('/dashboard/statistics', [DashboardStatisticApiController::class, 'index'])
        ->middleware('throttle:60,1')
        ->name('api.dashboard.statistics');

    Route::post('/aspirasi', [AspirasiSubmissionApiController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('api.aspirasi.store');
});