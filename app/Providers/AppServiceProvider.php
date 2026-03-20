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

        // Auto-run migration di production
        if (app()->environment('production')) {
            \DB::statement("SET SESSION sql_mode='STRICT_TRANS_TABLES'");

            // Check jika migrations table blum ada, jalankan migrate
            try {
                if (!Schema::hasTable('migrations')) {
                    // Jalankan migration
                    \Artisan::call('migrate', ['--force' => true]);
                }
            } catch (\Exception $e) {
                \Log::error('Migration error: ' . $e->getMessage());
            }
        }

        // Railway berada di belakang proxy, paksa URL HTTPS agar asset Vite tidak mixed-content.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
