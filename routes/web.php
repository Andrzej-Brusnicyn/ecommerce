<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

Route::get('/', fn() => view('welcome'))->name('home');

Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('auth.register');
    Route::post('/register', 'register');
    Route::get('/login', 'showLoginForm')->name('auth.login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('auth.logout')->middleware('auth');
});

Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductsController::class, 'index'])->name('index');
    Route::get('/{product}', [ProductsController::class, 'show'])->name('show');
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::post('/', [ProductsController::class, 'store'])->name('store');
        Route::put('/{product}', [ProductsController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductsController::class, 'destroy'])->name('destroy');
    });
});

Route::prefix('cart')->name('cart.')->middleware('auth')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'addToCart'])->name('add');
    Route::patch('/items/{cartItem}', [CartController::class, 'updateQuantity'])->name('update');
    Route::delete('/items/{cartItem}', [CartController::class, 'removeItem'])->name('remove');
});

Route::post('/order', [OrderController::class, 'createOrder'])
    ->name('order.create')
    ->middleware('auth');
