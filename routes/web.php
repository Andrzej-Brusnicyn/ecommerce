<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::prefix('products')->group(function () {
    Route::get('/', [ProductsController::class, 'index']);
    Route::get('/{product}', [ProductsController::class, 'show'])->name('products.show');
    Route::post('/', [ProductsController::class, 'store'])->name('products.store')->middleware(['auth:sanctum', 'role:admin']);
    Route::put('/{product}', [ProductsController::class, 'update'])->name('products.update')->middleware(['auth:sanctum', 'role:admin']);
    Route::delete('/{product}', [ProductsController::class, 'destroy'])->name('products.destroy')->middleware(['auth:sanctum', 'role:admin']);
});

