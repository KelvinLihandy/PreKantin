<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\View;
use Midtrans\Config; // ← Tambahkan ini

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Navbar Composer
        View::composer('*', function ($view) {
            $view->with('navbarData', BaseController::getNavbarData());
        });

        Config::$serverKey    = config('midtrans.serverKey');
        Config::$clientKey    = config('midtrans.clientKey');
        Config::$isProduction = config('midtrans.isProduction');

        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
}
