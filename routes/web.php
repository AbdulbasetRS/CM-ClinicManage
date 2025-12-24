<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\WelcomeController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {
        // Load admin routes
        require __DIR__ . '/admin.php';

        Route::controller(WelcomeController::class)->as('frontend.')->group(function () {
            Route::get('/', 'home')->name('home');
        });

        // Public fallback (must be last within localized group)
        Route::fallback(function () {
            return response()->view('errors.public-404', [], 404);
        });
    }
);
