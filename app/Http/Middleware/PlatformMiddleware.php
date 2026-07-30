<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PlatformMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->isPlatformAdmin()) {
            abort(403);
        }

        return $next($request);
    }
}