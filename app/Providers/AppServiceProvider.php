<?php

namespace App\Providers;

use App\Database\CustomPostgresConnector;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            RoleRepositoryInterface::class,
            RoleRepository::class
        );

        // Custom connector supaya parameter endpoint Neon (dibutuhkan untuk
        // client yang belum support SNI) beneran masuk ke connection string.
        $this->app->bind('db.connector.pgsql', function () {
            return new CustomPostgresConnector;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('pagination.shared');
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
