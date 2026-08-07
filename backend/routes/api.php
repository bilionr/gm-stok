<?php

use App\Http\Controllers\LogController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\BarangController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API is working',
    ]);
});

Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
Route::get('/logs/{log}/entries', [EntryController::class, 'index']);
Route::put('/logs/{log}/entries/update', [EntryController::class, 'update']);
Route::post('/logs', [LogController::class, 'store']);
Route::delete('/logs/{log}', [LogController::class, 'destroy']);
Route::post('/logs/{log}/entries', [EntryController::class, 'store']);
Route::delete('/logs/{log}/entries/{entry}', [EntryController::class, 'destroy']);
Route::get('/logs/{log}/available-barang-locations', [EntryController::class, 'availableBarangLocations']);

Route::get('/barangs', [BarangController::class, 'index']);
