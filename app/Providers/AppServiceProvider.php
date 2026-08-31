<?php

namespace App\Providers;

use App\Database\CustomPostgresConnector;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

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
        RateLimiter::for('login', function (Request $request) {
            $identity = strtolower(trim((string) ($request->input('login')
                ?? $request->input('employee_number')
                ?? 'guest')));

            return [
                Limit::perMinute(10)->by('login-ip:'.$request->ip()),
                Limit::perMinute(5)->by('login-identity:'.$request->ip().'|'.$identity),
            ];
        });

        RateLimiter::for('webhook', fn (Request $request) => [
            Limit::perMinute(120)->by('midtrans:'.$request->ip()),
        ]);
    }
}