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
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    /*
    |--------------------------------------------------------------------------
    | Method Not Allowed (405) — misal user refresh/back ke URL yang
    | cuma nerima PATCH/POST/DELETE tapi diakses via GET
    |--------------------------------------------------------------------------
    */

    $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {

        if ($request->is('api/*')) {
            return null;
        }

        return redirect()
            ->to(url()->previous() !== url()->current() ? url()->previous() : '/')
            ->with('error', 'Halaman tidak bisa diakses langsung, silakan ulangi aksinya.');

    });

    /*
    |--------------------------------------------------------------------------
    | Not Found (404) — halaman/route yang gak ada
    |--------------------------------------------------------------------------
    */

    $exceptions->render(function (NotFoundHttpException $e, Request $request) {

        if ($request->is('api/*')) {
            return null;
        }

        return redirect()
            ->to(url()->previous() !== url()->current() ? url()->previous() : '/')
            ->with('error', 'Halaman yang kamu cari tidak ditemukan.');

    });

})
->withMiddleware(function (Middleware $middleware) {
    $middleware->trustProxies(at: '*');
})
->create();