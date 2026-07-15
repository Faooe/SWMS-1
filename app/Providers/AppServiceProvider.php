<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
{
    $this->app->bind(
        \App\Repositories\Interfaces\RoleRepositoryInterface::class,
        \App\Repositories\Eloquent\RoleRepository::class
    );
}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
