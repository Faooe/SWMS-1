<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

abstract class AuthenticatedProfileController extends Controller
{
    public function __construct(
        protected readonly ProfileService $profileService
    ) {}

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $this->profileService->updateProfile(
            $request->user(),
            $request->validated()
        );

        return back()->with('success', 'Profile berhasil diperbarui.');
    }

    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:200'],
        ]);

        $this->profileService->updatePhoto(
            $request->user(),
            $request->file('photo')
        );

        return back()->with('success', 'Foto profile berhasil diperbarui.');
    }
}
