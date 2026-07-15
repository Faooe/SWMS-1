<?php

use App\Http\Middleware\EmployeeMiddleware;
use App\Http\Middleware\PlatformMiddleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\CheckCompanyActive;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(
    basePath: dirname(__DIR__)
)
->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
->withMiddleware(function (Middleware $middleware): void {

    /*
    |--------------------------------------------------------------------------
    | Global Group Middleware (Pengecekan Status Perusahaan)
    |--------------------------------------------------------------------------
    */
    
    // Berjalan di setiap request halaman web
    $middleware->web(append: [
        CheckCompanyActive::class,
    ]);

    // Berjalan di setiap request endpoint API (Mobile App)
    $middleware->api(append: [
        CheckCompanyActive::class,
    ]);

    /*
    |--------------------------------------------------------------------------
    | Middleware Alias
    |--------------------------------------------------------------------------
    */

    $middleware->alias([

        /*
        |--------------------------------------------------------------------------
        | Generic Role
        |--------------------------------------------------------------------------
        */

        'role' => RoleMiddleware::class,

        /*
        |--------------------------------------------------------------------------
        | SaaS
        |--------------------------------------------------------------------------
        */

        'platform' => PlatformMiddleware::class,

        'superadmin' => SuperAdminMiddleware::class,

        'employee' => EmployeeMiddleware::class,

    ]);

})
->withExceptions(function (Exceptions $exceptions): void {

    $exceptions->shouldRenderJsonWhen(
        fn (Request $request) => $request->is('api/*'),
    );

})
->create();