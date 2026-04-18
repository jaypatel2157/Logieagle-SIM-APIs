<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StockController;

Route::prefix('categories')->group(function () {
    Route::get('/tree', [CategoryController::class, 'tree']);
});

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}/movements', [ProductController::class, 'movements']);
});

Route::prefix('stock')->group(function () {
    Route::post('/adjust', [StockController::class, 'adjust']);
});

Route::prefix('inventory')->group(function () {
    Route::get('/summary', [InventoryController::class, 'summary']);
    Route::get('/low-stock', [InventoryController::class, 'lowStock']);
});