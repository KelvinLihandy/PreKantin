<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\KantinController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\PaymentController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

Route::get('/', [BaseController::class, 'homePage'])->name('home.page');
Route::get('/about', [BaseController::class, 'aboutPage'])->name('about.page');
Route::get('/kantin', [KantinController::class, 'kantinListPage'])->name('kantin.list');
Route::get('/kantin/{id}', [KantinController::class, 'kantinPage'])->name('kantin.page');
Route::post('/payment/callback', [PaymentController::class, 'handleCallback'])->name('payment.callback')
    ->withoutMiddleware(VerifyCsrfToken::class);;

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'registerPage'])->name('register.page');
    Route::post('/register', [AuthController::class, 'registerAccount'])->name('register.do');
    Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
    Route::post('/login', [AuthController::class, 'loginAccount'])->name('login.do');
    Route::get('/forgot-password', [AuthController::class, 'forgotPage'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'resetPage'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/change-password', [AuthController::class, 'changePage'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.changed');

    Route::get('/order-history', [OrderHistoryController::class, 'index'])->name('order.history');
    Route::get('/order-history/{order}', [OrderHistoryController::class, 'show'])->name('order.detail');
    Route::post('/order/{id}/status', [OrderHistoryController::class, 'updateStatus'])->name('order.update');
    
    Route::get('/merchant/order-history', [OrderHistoryController::class, 'merchantIndex'])->name('merchant.order.history');
    Route::get('/merchant/order-history/{order}', [OrderHistoryController::class, 'merchantShow'])->name('merchant.order.detail');
    Route::post('/merchant/menu', [KantinController::class, 'addMenu'])->name('menu.add');
    Route::delete('/merchant/menu/{id}', [KantinController::class, 'removeMenu'])->name('menu.remove');
    Route::post('/merchant/name', [MerchantController::class, 'saveName'])->name('merchant.name');
    Route::post('/merchant/time', [MerchantController::class, 'saveTime'])->name('merchant.time');
    Route::post('/merchant/image', [MerchantController::class, 'saveImage'])->name('merchant.image');

    Route::post('/order', [OrderController::class, 'order'])->name('order.do');
    Route::post('order/create', [OrderController::class, 'store'])->name('order.create');
    Route::delete('order/{id}', [OrderController::class, 'remove'])->name('order.remove');
});
