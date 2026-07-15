<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {
    }

    /**
     * Login
     */
    public function login(
        LoginRequest $request
    ): JsonResponse {

        $result = $this->authService->loginApi(
            $request->validated(),
            $request
        );

        return ResponseHelper::success(

            [
                'token' => $result['token'],
                'user' => new UserResource($result['user']),
            ],

            'Login berhasil.'

        );
    }

    /**
     * Get authenticated user.
     */
    public function me(
        Request $request
    ): JsonResponse {

        /** @var User|null $user */
        $user = $request->user();

        if (!$user) {

            return ResponseHelper::error(
                'Unauthenticated.',
                null,
                401
            );

        }

        $user->load([
            'role',
            'employee.currentEmployment.department',
            'employee.currentEmployment.position',
            'employee.currentEmployment.team',
            'employee.currentEmployment.office',
            'employee.currentEmployment.shift',
        ]);

        return ResponseHelper::success(

            new UserResource($user),

            'Data user berhasil diambil.'

        );
    }

    /**
     * Logout
     */
    public function logout(
        Request $request
    ): JsonResponse {

        /** @var User|null $user */
        $user = $request->user();

        if (!$user) {

            return ResponseHelper::error(
                'Unauthenticated.',
                null,
                401
            );

        }

        $this->authService->logout($user);

        return ResponseHelper::success(

            null,

            'Logout berhasil.'

        );
    }

    /**
     * Change Password
     */
    public function changePassword(
        ChangePasswordRequest $request
    ): JsonResponse {

        /** @var User|null $user */
        $user = $request->user();

        if (!$user) {

            return ResponseHelper::error(
                'Unauthenticated.',
                null,
                401
            );

        }

        $this->authService->changePassword(
            $user,
            $request->validated()
        );

        return ResponseHelper::success(

            new UserResource($user->fresh()),

            'Password berhasil diubah.'

        );
    }
}