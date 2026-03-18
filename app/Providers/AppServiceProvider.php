<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Railway berada di belakang proxy, paksa URL HTTPS agar asset Vite tidak mixed-content.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
