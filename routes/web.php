<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\KantinController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\PaymentController;

Route::get('/', [BaseController::class, 'homePage'])->name('home.page');

Route::middleware('guest')->group(function(){
    Route::get('/register', [AuthController::class, 'registerPage'])->name('register.page');
    Route::post('/register', [AuthController::class, 'registerAccount'])->name('register.do');
    Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
    Route::post('/login', [AuthController::class, 'loginAccount'])->name('login.do');
    Route::get('/about', [BaseController::class, 'aboutPage'])->name('about.page');
    Route::get('/forgot-password', [AuthController::class, 'forgotPage'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'resetPage'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function(){
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/change-password', [AuthController::class, 'changePage'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.changed');
    Route::get('/kantin/{id}', [KantinController::class, 'kantinPage'])->name('kantin.page');

    Route::get('/order-history', [OrderHistoryController::class, 'index'])->name('order.history');
    Route::get('/order-history/{order}', [OrderHistoryController::class, 'show'])->name('order.detail');
});
Route::post('/order/add', [App\Http\Controllers\OrderController::class, 'addOrder'])
    ->name('order.add');
Route::post('/order/create', [OrderController::class, 'store']);
Route::post('/order/create', [OrderController::class, 'store'])->name('order.create');
Route::post('/payment/create-qris', [PaymentController::class, 'createQris']);





