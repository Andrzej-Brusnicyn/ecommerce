<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('welcome');
});

    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum');

Route::prefix('products')->group(function () {
    Route::get('/', [ProductsController::class, 'index'])->name('catalog');
    Route::get('/{product}', [ProductsController::class, 'show']);
    Route::post('/', [ProductsController::class, 'store'])->middleware(['auth:sanctum', 'role:admin']);
    Route::put('/{product}', [ProductsController::class, 'update'])->middleware(['auth:sanctum', 'role:admin']);
    Route::delete('/{product}', [ProductsController::class, 'destroy'])->middleware(['auth:sanctum', 'role:admin']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::patch('/cart/items/{cartItem}', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::delete('/cart/items/{cartItem}', [CartController::class, 'removeItem'])->name('cart.remove');
});

Route::post('/order', [OrderController::class, 'createOrder'])->name('order')->middleware('auth:sanctum');
