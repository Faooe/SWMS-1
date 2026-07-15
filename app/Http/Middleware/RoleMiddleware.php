<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {

        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {

            abort(401);

        }

        if (!$user->hasRole(...$roles)) {

            abort(403);

        }

        return $next($request);

    }
}