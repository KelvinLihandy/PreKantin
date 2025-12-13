<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\KantinController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\MerchantMenuController;
use App\Http\Controllers\PaymentController;

Route::get('/', [BaseController::class, 'homePage'])->name('home.page');
Route::get('/kantin', [KantinController::class, 'kantinListPage'])->name('kantin.list');
Route::get('/kantin/{id}', [KantinController::class, 'kantinPage'])->name('kantin.page');

Route::middleware('guest')->group(function () {
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

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/change-password', [AuthController::class, 'changePage'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.changed');

    // Upload image menu ke Supabase
    Route::post('/merchant/{id}/menu/upload-image', [KantinController::class, 'uploadMenuImage'])->name('menu.upload');

    // Tambah menu ke Supabase
    Route::post('/merchant/{id}/menu/add', [KantinController::class, 'addMenu'])->name('menu.add');

    Route::get('/order-history', [OrderHistoryController::class, 'index'])->name('order.history');
    Route::get('/order-history/{order}', [OrderHistoryController::class, 'show'])->name('order.detail');

    Route::get('/merchant/order-history', [OrderHistoryController::class, 'merchantIndex'])->name('merchant.order.history');
    Route::post('/merchant/order/{id}/status', [OrderHistoryController::class, 'updateStatus'])->name('merchant.order.update');

    Route::get('/merchant/order/{order}', [OrderHistoryController::class, 'merchantShow'])->name('merchant.order.detail');

    Route::get('/merchant/menu', [MerchantMenuController::class, 'index'])->name('merchant.menu.index');
    Route::post('/merchant/menu', [MerchantMenuController::class, 'store'])->name('merchant.menu.store');

    Route::post('/order/add', [OrderController::class, 'addOrder'])->name('order.add');
    Route::post('/order/create', [OrderController::class, 'store'])->name('order.store');
    Route::post('/payment/create-qris', [PaymentController::class, 'createQris']);
});
