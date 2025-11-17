<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BaseController::class, 'homePage'])->name('home.page');
Route::get('/register', [AuthController::class, 'registerPage'])->name('register.page');
Route::post('/register', [AuthController::class, 'registerAccount'])->name('register.do');
Route::get('/login', [AuthController::class, 'loginPage'])->name('login.page');
Route::post('/login', [AuthController::class, 'loginAccount'])->name('login.do');
Route::get('/about', [BaseController::class, 'aboutPage'])->name('about.page');