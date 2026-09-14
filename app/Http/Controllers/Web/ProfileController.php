<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends AuthenticatedProfileController
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $this->profileService->profile($request->user()),
        ]);
    }
}
