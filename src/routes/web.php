<?php

use App\Http\Controllers\AspirasiPdfController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\TrackingController;
use App\Http\Controllers\Frontend\AspirasiSubmissionController;
// use App\Http\Controllers\Frontend\PublicStatisticController;
use App\Http\Controllers\Admin\AspirasiExportController;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

/* NOTE: Do Not Remove
/ Livewire asset handling if using sub folder in domain
*/
Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});
/*
/ END
*/
Route::get('/', [HomeController::class, 'index'])
    ->name('frontend.home');

Route::get('/tracking', [TrackingController::class, 'show'])
    ->name('frontend.tracking.show');

Route::post('/aspirasi', [AspirasiSubmissionController::class, 'store'])
    ->name('frontend.aspirasi.store');

Route::get('/aspirasi/sukses/{aspirasi}', [AspirasiSubmissionController::class, 'success'])
    ->name('frontend.aspirasi.success');

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/admin/aspirasi/{aspirasi}/pdf', [AspirasiPdfController::class, 'show'])
    ->middleware(['web', 'auth'])
    ->name('aspirasi.pdf');

Route::view('/kebijakan-privasi', 'frontend.privacy')
    ->name('frontend.privacy');

Route::get('/tracking/{aspirasi}/pdf', [AspirasiPdfController::class, 'public'])
    ->name('frontend.tracking.pdf');

Route::get('/admin/aspirasi/export/excel', [AspirasiExportController::class, 'excel'])
    ->middleware(['web', 'auth'])
    ->name('admin.aspirasi.export.excel');

// Route::get('/statistik', [PublicStatisticController::class, 'index'])
//     ->name('frontend.statistics.index');
