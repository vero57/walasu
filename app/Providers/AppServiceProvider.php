<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SiswaMiddleware;
use App\Http\Middleware\WalasMiddleware;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.RouteServiceProvider
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
        // Middleware untuk siswa
        Route::aliasMiddleware('siswa', SiswaMiddleware::class);
    
        // Middleware untuk walas
        Route::aliasMiddleware('walas', WalasMiddleware::class);
    }
}
