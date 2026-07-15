<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Http\Requests\Platform\ChangePasswordRequest;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(
        protected ProfileService $profileService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Profile Page
    |--------------------------------------------------------------------------
    */

    public function edit(Request $request)
    {
        return view(

            'platform.profile.index',

            [

                'user' => $this->profileService->profile(

                    $request->user()

                ),

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Password
    |--------------------------------------------------------------------------
    */

    public function update(
        ChangePasswordRequest $request
    ) {

        $this->profileService->changePassword(

            $request->user(),

            $request->validated()

        );

        return redirect()

            ->route('platform.profile.edit')

            ->with(

                'success',

                'Password berhasil diperbarui.'

            );

    }
}