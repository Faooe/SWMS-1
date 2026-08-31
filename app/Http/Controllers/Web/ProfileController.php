<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Edit Profile
    |--------------------------------------------------------------------------
    */

    public function edit()
    {
        return view(

            'profile.edit',

            [

                'user' => User::query()

                    ->with('employee')

                    ->findOrFail(Auth::id()),

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Profile
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request
    ) {

        $user = User::query()->findOrFail(Auth::id());

        $request->validate([

            'username' => [

                'required',

                'string',

                'max:50',

                'unique:users,username,' . $user->id,

            ],

            'email' => [

                'required',

                'email',

                'unique:users,email,' . $user->id,

            ],

            'current_password' => [

                'required_with:password',

                'current_password',

            ],

            'password' => [

                'nullable',

                'confirmed',

                Password::min(8)->letters()->mixedCase()->numbers(),

            ],

        ]);

        $data = [

            'username' => $request->username,

            'email' => $request->email,

        ];

        if ($request->filled('password')) {

            $data['password'] = bcrypt(

                $request->password

            );

            $data['password_changed_at'] = now();

        }

        $user->update($data);

        if ($request->filled('password')) {
            // Pertahankan web session saat ini, tetapi cabut seluruh token
            // mobile/API agar perangkat lain wajib login ulang.
            $user->tokens()->delete();
            $user->forceFill(['fcm_token' => null])->save();

            Log::notice('Web profile password changed; API sessions revoked.', [
                'user_id' => $user->id,
                'company_id' => $user->company_id,
            ]);
        }

        return back()->with(

            'success',

            'Profile berhasil diperbarui.'

        );

    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ]);
        app(ProfileService::class)->updatePhoto($request->user(), $request->file('photo'));
        return back()->with('success', 'Foto profile berhasil diperbarui.');
    }
}
