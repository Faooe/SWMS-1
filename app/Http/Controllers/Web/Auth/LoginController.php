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
        | Tentukan mode login: 'email' (default, Admin/Company Admin/employee
        | yang punya email) atau 'employee' (Kode Company + NIP, khusus
        | employee yang tidak/belum punya email). Lihat toggle di
        | resources/views/auth/login.blade.php -- field hidden 'login_mode'.
        |--------------------------------------------------------------------------
        */

        $mode = $request->input('login_mode', 'email');

        if ($mode === 'employee') {

            $credentials = $request->validate([

                'company_code' => ['required', 'string', 'max:30'],

                'employee_number' => ['required', 'string', 'max:30'],

                'password' => ['required', 'string'],

            ], [
                'company_code.required' => 'Kode Company wajib diisi.',
                'employee_number.required' => 'NIP wajib diisi.',
            ]);

            $success = $this->authService->loginEmployeeWeb(

                $credentials,

                $request

            );

            if (!$success) {

                return back()

                    ->withErrors([

                        'login' => 'Kode Company, NIP, atau Password salah.',

                    ])

                    ->withInput();

            }

        } else {

            /*
            |--------------------------------------------------------------------------
            | Validation
            |--------------------------------------------------------------------------
            */

            $credentials = $request->validate([

                'login' => [
                    'required',
                    'string',
                    'email',
                ],

                'password' => [
                    'required',
                    'string',
                ],

            ], [
                'login.required' => 'Email wajib diisi.',
                'login.email' => 'Masukkan alamat email yang valid.',
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

                        'login' => 'Email atau Password salah.',

                    ])

                    ->withInput();

            }

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
        | Company Active
        |--------------------------------------------------------------------------
        */

        if ($user->company_id && (!$user->company || !$user->company->is_active)) {

            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect()

                ->route('login')

                ->withErrors([

                    'login' => 'Perusahaan Anda telah dinonaktifkan oleh Administrator.',

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