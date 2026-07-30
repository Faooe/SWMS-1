<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    /**
     * Platform Admin only
     */
    private function isPlatform(
        User $user
    ): bool {

        return $user->role?->code === 'PLATFORM_ADMIN';

    }

    /*
    |--------------------------------------------------------------------------
    | View Any
    |--------------------------------------------------------------------------
    */

    public function viewAny(
        User $user
    ): bool {

        return $this->isPlatform($user);

    }

    /*
    |--------------------------------------------------------------------------
    | View
    |--------------------------------------------------------------------------
    */

    public function view(
        User $user,
        Company $company
    ): bool {

        return $this->isPlatform($user);

    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(
        User $user
    ): bool {

        return $this->isPlatform($user);

    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        User $user,
        Company $company
    ): bool {

        return $this->isPlatform($user);

    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function delete(
        User $user,
        Company $company
    ): bool {

        return $this->isPlatform($user);

    }

    /*
    |--------------------------------------------------------------------------
    | Restore
    |--------------------------------------------------------------------------
    */

    public function restore(
        User $user,
        Company $company
    ): bool {

        return false;

    }

    /*
    |--------------------------------------------------------------------------
    | Force Delete
    |--------------------------------------------------------------------------
    */

    public function forceDelete(
        User $user,
        Company $company
    ): bool {

        return false;

    }
}