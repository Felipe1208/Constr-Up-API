<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json([
        'message' => 'API is working',
    ]);
});

Route::prefix('product')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::get('{productId}', [ProductController::class, 'find']);
    Route::put('{productId}', [ProductController::class, 'update']);
    Route::delete('{productId}', [ProductController::class, 'delete']);
});
