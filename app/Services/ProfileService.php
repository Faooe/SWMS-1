<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileService
{
    /*
    |--------------------------------------------------------------------------
    | Get Profile
    |--------------------------------------------------------------------------
    */

    public function profile(User $user): User
    {
        return $user->load([

            'role',

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Change Password
    |--------------------------------------------------------------------------
    */

    public function changePassword(
        User $user,
        array $data
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Check Current Password
        |--------------------------------------------------------------------------
        */

        if (! Hash::check(

            $data['current_password'],

            $user->password

        )) {

            throw ValidationException::withMessages([

                'current_password' =>

                    'Password lama tidak sesuai.',

            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Update Password
        |--------------------------------------------------------------------------
        */

        $user->update([

            'password' => Hash::make(

                $data['password']

            ),

        ]);

    }
}