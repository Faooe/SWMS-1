<?php

namespace App\Http\Controllers\Web\Employee;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

            'employee.profile.index',

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

                'min:6',

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

        return back()->with(

            'success',

            'Profile berhasil diperbarui.'

        );

    }
}