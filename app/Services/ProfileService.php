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

            'company',

            'employee.currentEmployment.department',

            'employee.currentEmployment.position',

            'employee.currentEmployment.team',

            'employee.currentEmployment.office',

            'employee.currentEmployment.shift',

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Profile (username / email / password opsional)
    |--------------------------------------------------------------------------
    |
    | Dipakai oleh SEMUA role (Platform Admin, Company Admin/Super Admin,
    | Employee) lewat satu endpoint API yang sama, menyesuaikan logika
    | yang sebelumnya hanya ada di Web\ProfileController::update().
    |
    */

    public function updateProfile(
        User $user,
        array $data
    ): User {

        if (
            !empty($data['password'])
            && empty($data['current_password'])
        ) {

            throw ValidationException::withMessages([

                'current_password' =>
                    'Password lama wajib diisi untuk mengubah password.',

            ]);

        }

        if (
            !empty($data['current_password'])
            && !Hash::check($data['current_password'], $user->password)
        ) {

            throw ValidationException::withMessages([

                'current_password' =>
                    'Password lama tidak sesuai.',

            ]);

        }

        $updateData = [

            'username' => $data['username'],

            'email' => $data['email'],

        ];

        if (!empty($data['password'])) {

            $updateData['password'] = Hash::make(
                $data['password']
            );

            $updateData['password_changed_at'] = now();

        }

        $user->update($updateData);

        return $this->profile($user->fresh());

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
