<?php

namespace App\Providers;

use App\Database\CustomPostgresConnector;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Interfaces\RoleRepositoryInterface::class,
            \App\Repositories\Eloquent\RoleRepository::class
        );

        // Custom connector supaya parameter endpoint Neon (dibutuhkan untuk
        // client yang belum support SNI) beneran masuk ke connection string.
        $this->app->bind('db.connector.pgsql', function () {
            return new CustomPostgresConnector();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}