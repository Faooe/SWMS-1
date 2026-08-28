<?php

namespace App\Http\Controllers\Api\V1\Profile;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Profile Controller (Generic, Semua Role)
    |--------------------------------------------------------------------------
    |
    | Endpoint ini dipakai oleh Platform Admin, Company Admin (Super
    | Admin), maupun Employee -- mengikuti pola ProfileService yang
    | sudah role-agnostic (dipakai juga oleh Platform\ProfileController
    | di web). Sebelumnya di API hanya tersedia ubah password
    | (/change-password); endpoint ini menambah lihat & ubah profile
    | (username / email) yang sebelumnya cuma ada di web.
    |
    */

    public function __construct(
        protected ProfileService $profileService
    ) {
    }

    /**
     * Get Profile
     */
    public function show(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user();

        if (!$user) {

            return ResponseHelper::error(
                'Unauthenticated.',
                null,
                401
            );

        }

        return ResponseHelper::success(

            new UserResource(
                $this->profileService->profile($user)
            ),

            'Profile berhasil diambil.'

        );
    }

    /**
     * Update Profile
     */
    public function update(
        UpdateProfileRequest $request
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

        $updated = $this->profileService->updateProfile(
            $user,
            $request->validated()
        );

        return ResponseHelper::success(

            new UserResource($updated),

            'Profile berhasil diperbarui.'

        );
    }

    /** Upload / replace profile photo for any authenticated role. */
    public function updatePhoto(Request $request): JsonResponse
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ]);

        /** @var User $user */
        $user = $request->user();
        $updated = $this->profileService->updatePhoto($user, $request->file('photo'));

        return ResponseHelper::success(
            new UserResource($updated),
            'Foto profile berhasil diperbarui.'
        );
    }
}
