<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockEntryController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API is working',
    ]);
});

Route::resource('items', ItemController::class);
Route::resource('stock-entry', StockEntryController::class);