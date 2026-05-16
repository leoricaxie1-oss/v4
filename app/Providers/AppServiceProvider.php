<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // utf8mb4 + indexed string columns: keeps key length under MariaDB/MySQL 5.x 767-byte limit
        // (191 * 4 bytes = 764). Required for XAMPP's bundled MariaDB to run the migrations cleanly.
        Schema::defaultStringLength(191);

        Paginator::useTailwind();

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
