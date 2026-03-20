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

        // Auto-run migration di production (aman untuk MySQL/PostgreSQL)
        if (app()->environment('production')) {
            try {
                $driver = config('database.default');

                // sql_mode hanya valid untuk MySQL/MariaDB.
                if (in_array($driver, ['mysql', 'mariadb'], true)) {
                    \DB::statement("SET SESSION sql_mode='STRICT_TRANS_TABLES'");
                }

                // Check jika migrations table belum ada, jalankan migrate.
                if (!Schema::hasTable('migrations')) {
                    \Artisan::call('migrate', ['--force' => true]);
                }
            } catch (\Exception $e) {
                \Log::warning('Production bootstrap DB step skipped: '.$e->getMessage());
            }
        }

        // Railway berada di belakang proxy, paksa URL HTTPS agar asset Vite tidak mixed-content.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
