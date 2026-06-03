<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        if (!defined('L5_SWAGGER_CONST_HOST')) {
            define('L5_SWAGGER_CONST_HOST', config('app.url') . '/api');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
