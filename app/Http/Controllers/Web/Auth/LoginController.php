<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Login Page
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('auth.login');
    }

    /*
    |--------------------------------------------------------------------------
    | Authenticate
    |--------------------------------------------------------------------------
    */

    public function authenticate(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $credentials = $request->validate([

            'login' => [
                'required',
                'string',
            ],

            'password' => [
                'required',
                'string',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Attempt Login
        |--------------------------------------------------------------------------
        */

        $success = $this->authService->loginWeb(

            $credentials,

            $request

        );

        if (!$success) {

            return back()

                ->withErrors([

                    'login' => 'Username atau Password salah.',

                ])

                ->withInput();

        }

        /*
        |--------------------------------------------------------------------------
        | Current User
        |--------------------------------------------------------------------------
        */

        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {

            Auth::logout();

            return redirect()

                ->route('login')

                ->withErrors([

                    'login' => 'User tidak ditemukan.',

                ]);

        }

        /*
        |--------------------------------------------------------------------------
        | User Active
        |--------------------------------------------------------------------------
        */

        if (!$user->isActive()) {

            Auth::logout();

            return redirect()

                ->route('login')

                ->withErrors([

                    'login' => 'Akun Anda sudah tidak aktif.',

                ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Redirect By Role
        |--------------------------------------------------------------------------
        */

        if ($user->isPlatformAdmin()) {

            return redirect()->route(

                'platform.dashboard'

            );

        }

        if ($user->isSuperAdmin()) {

            return redirect()->route(

                'dashboard'

            );

        }

        if ($user->isEmployee()) {

            return redirect()->route(

                'employee.dashboard'

            );

        }

        /*
        |--------------------------------------------------------------------------
        | Unknown Role
        |--------------------------------------------------------------------------
        */

        Auth::logout();

        return redirect()

            ->route('login')

            ->withErrors([

                'login' => 'Role akun tidak dikenali.',

            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        $this->authService->logoutWeb(

            $request

        );

        return redirect()

            ->route('login');
    }
}