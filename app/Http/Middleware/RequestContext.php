<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class RequestContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $requestId = trim((string) $request->header('X-Request-ID')) ?: (string) Str::uuid();
        $request->attributes->set('request_id', $requestId);

        Log::withContext([
            'request_id' => $requestId,
            'method' => $request->method(),
            'path' => $request->path(),
            'user_id' => $request->user()?->id,
            'company_id' => $request->user()?->company_id,
        ]);

        $startedAt = microtime(true);

        try {
            /** @var Response $response */
            $response = $next($request);
        } catch (\Throwable $e) {
            Log::error('Unhandled request exception.', [
                'exception' => get_class($e),
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }

        $durationMs = (int) round((microtime(true) - $startedAt) * 1000);
        $response->headers->set('X-Request-ID', $requestId);

        if ($response->getStatusCode() >= 500) {
            Log::error('Request completed with server error.', [
                'status' => $response->getStatusCode(),
                'duration_ms' => $durationMs,
            ]);
        } elseif ($durationMs >= (int) config('app.slow_request_ms', 1500)) {
            Log::warning('Slow request detected.', [
                'status' => $response->getStatusCode(),
                'duration_ms' => $durationMs,
            ]);
        } else {
            Log::info('Request completed.', [
                'status' => $response->getStatusCode(),
                'duration_ms' => $durationMs,
            ]);
        }

        return $response;
    }
}
