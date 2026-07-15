<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCompanyActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah ada user yang sedang login
        if (Auth::check()) {
            $user = Auth::user();

            // 2. Jika user terikat dengan company, load relasi company-nya
            if ($user->company_id) {
                $company = $user->company;

                // 3. Jika company tidak aktif, langsung kick/logout secara paksa
                if ($company && !$company->is_active) {
                    
                    // Jika request datang dari API (Mobile App / Flutter)
                    if ($request->expectsJson()) {
                        /** @var \Laravel\Sanctum\PersonalAccessToken|null $token */
                        $token = $request->user()?->currentAccessToken();
                        
                        if ($token) {
                            $token->delete();
                        }
                        
                        return response()->json([
                            'message' => 'Perusahaan Anda telah dinonaktifkan. Sesi Anda berakhir.'
                        ], 403);
                    }

                    // Jika request datang dari Web Browser
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()
                        ->route('login')
                        ->withErrors([
                            'login' => 'Perusahaan Anda telah dinonaktifkan oleh Administrator.'
                        ]);
                }
            }
        }

        return $next($request);
    }
}