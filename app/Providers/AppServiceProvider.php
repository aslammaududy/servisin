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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->protectSqliteDatabase();
    }

    private function protectSqliteDatabase(): void
    {
        if (config('database.default') !== 'sqlite') {
            return;
        }

        $path = config('database.connections.sqlite.database');

        if ($path && file_exists($path) && decoct(fileperms($path) & 0777) !== '600') {
            chmod($path, 0600);
        }
    }
}
