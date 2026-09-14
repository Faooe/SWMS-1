<?php

namespace App\Http\Controllers\Web\Employee;

use App\Http\Controllers\Web\AuthenticatedProfileController;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends AuthenticatedProfileController
{
    public function edit(Request $request): View
    {
        return view('employee.profile.index', [
            'user' => $this->profileService->profile($request->user()),
        ]);
    }
}
