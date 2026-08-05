<?php

use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API is working',
    ]);
});

Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
Route::get('/logs/{log}', [LogController::class, 'show'])->name('logs.show');