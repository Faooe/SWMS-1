<?php

use App\Http\Middleware\EmployeeMiddleware;
use App\Http\Middleware\PlatformMiddleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\CheckCompanyActive;
use App\Http\Middleware\RequestContext;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

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
    | CORS (didaftarkan eksplisit, tidak mengandalkan default framework)
    |--------------------------------------------------------------------------
    |
    | Harus di-prepend paling depan supaya preflight OPTIONS request
    | ditangani SEBELUM request masuk ke router -- kalau tidak, request
    | OPTIONS akan kena 404 karena tidak ada route yang menerima method
    | OPTIONS secara eksplisit.
    |
    */

    $middleware->prepend(HandleCors::class);
    $middleware->prepend(RequestContext::class);
    $middleware->prepend(SecurityHeaders::class);

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

    // Vercel / reverse proxy support. Keep in this same middleware callback.
    $middleware->trustProxies(at: '*');

})
->withExceptions(function (Exceptions $exceptions): void {

    $exceptions->shouldRenderJsonWhen(
        fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
    );

    $exceptions->render(function (ValidationException $e, Request $request) {
        if (!($request->is('api/*') || $request->expectsJson())) {
            return null;
        }

        return response()->json([
            'success' => false,
            'message' => 'Data yang dikirim belum valid.',
            'errors' => $e->errors(),
            'request_id' => $request->attributes->get('request_id'),
        ], 422);
    });

    $exceptions->render(function (AuthenticationException $e, Request $request) {
        if (!($request->is('api/*') || $request->expectsJson())) {
            return null;
        }

        return response()->json([
            'success' => false,
            'message' => 'Sesi tidak valid atau sudah berakhir. Silakan login kembali.',
            'errors' => null,
            'request_id' => $request->attributes->get('request_id'),
        ], 401);
    });

    $exceptions->render(function (AuthorizationException $e, Request $request) {
        if (!($request->is('api/*') || $request->expectsJson())) {
            return null;
        }

        return response()->json([
            'success' => false,
            'message' => 'Kamu tidak memiliki akses untuk melakukan aksi ini.',
            'errors' => null,
            'request_id' => $request->attributes->get('request_id'),
        ], 403);
    });

    $exceptions->render(function (ModelNotFoundException $e, Request $request) {
        if (!($request->is('api/*') || $request->expectsJson())) {
            return null;
        }

        return response()->json([
            'success' => false,
            'message' => 'Data yang diminta tidak ditemukan.',
            'errors' => null,
            'request_id' => $request->attributes->get('request_id'),
        ], 404);
    });

    $exceptions->render(function (HttpExceptionInterface $e, Request $request) {
        if (!($request->is('api/*') || $request->expectsJson())) {
            return null;
        }

        $status = $e->getStatusCode();
        $message = match ($status) {
            401 => 'Sesi tidak valid atau sudah berakhir. Silakan login kembali.',
            403 => 'Kamu tidak memiliki akses untuk melakukan aksi ini.',
            404 => 'Endpoint atau data yang diminta tidak ditemukan.',
            405 => 'Metode request tidak diizinkan untuk endpoint ini.',
            429 => 'Terlalu banyak percobaan. Tunggu sebentar lalu coba lagi.',
            default => $status >= 500
                ? 'Terjadi gangguan pada server. Silakan coba lagi.'
                : 'Permintaan tidak dapat diproses.',
        };

        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => null,
            'request_id' => $request->attributes->get('request_id'),
        ], $status);
    });

    $exceptions->render(function (\Throwable $e, Request $request) {
        if (!($request->is('api/*') || $request->expectsJson())) {
            return null;
        }

        Log::error('API exception rendered.', [
            'request_id' => $request->attributes->get('request_id'),
            'exception' => get_class($e),
            'error' => $e->getMessage(),
        ]);

        return response()->json([
            'success' => false,
            'message' => app()->hasDebugModeEnabled()
                ? $e->getMessage()
                : 'Terjadi gangguan pada server. Silakan coba lagi.',
            'errors' => null,
            'request_id' => $request->attributes->get('request_id'),
        ], 500);
    });

    // Final response hook for exceptions: middleware post-processing does not
    // execute when an inner middleware throws (for example auth 401).
    $exceptions->respond(function (SymfonyResponse $response): SymfonyResponse {
        $request = request();
        $requestId = $request->attributes->get('request_id');

        if ($requestId && !$response->headers->has('X-Request-ID')) {
            $response->headers->set('X-Request-ID', (string) $requestId);
        }

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(self), geolocation=(self), microphone=()');

        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    });

})
->create();
