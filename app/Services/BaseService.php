<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseService
{
    /*
    |--------------------------------------------------------------------------
    | Current User
    |--------------------------------------------------------------------------
    */

    protected function currentUser()
    {
        return Auth::user();
    }

    /*
    |--------------------------------------------------------------------------
    | Current Company ID
    |--------------------------------------------------------------------------
    */

    protected function companyId(): ?int
    {
        return $this->currentUser()?->company_id;
    }

    /*
    |--------------------------------------------------------------------------
    | Fill Company
    |--------------------------------------------------------------------------
    |
    | Digunakan ketika create data baru.
    | company_id akan otomatis mengikuti company user yang login.
    |
    */

    protected function fillCompany(array &$data): void
    {
        $user = $this->currentUser();

        if (!$user) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Platform Admin
        |--------------------------------------------------------------------------
        |
        | Platform Admin boleh menentukan company sendiri.
        |
        */

        if (
            method_exists($user, 'isPlatformAdmin')
            && $user->isPlatformAdmin()
        ) {
            return;
        }

        $data['company_id'] = $user->company_id;
    }

    /*
    |--------------------------------------------------------------------------
    | Authorize Company
    |--------------------------------------------------------------------------
    |
    | Memastikan data yang akan diubah benar-benar milik company
    | yang sedang login.
    |
    */

    protected function authorizeCompany(Model $model): void
    {
        $user = $this->currentUser();

        if (!$user) {
            throw new HttpException(
                401,
                'Unauthenticated.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Platform Admin
        |--------------------------------------------------------------------------
        */

        if (
            method_exists($user, 'isPlatformAdmin')
            && $user->isPlatformAdmin()
        ) {
            return;
        }

        if ($model->company_id != $user->company_id) {

            throw new HttpException(
                403,
                'You are not authorized to access this data.'
            );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Current Company Query
    |--------------------------------------------------------------------------
    |
    | Helper untuk query builder jika sewaktu-waktu diperlukan.
    |
    */

    protected function applyCompanyScope(
        Builder $query
    ): Builder
    {
        $user = $this->currentUser();

        if (!$user) {
            return $query;
        }

        if (
            method_exists($user, 'isPlatformAdmin')
            && $user->isPlatformAdmin()
        ) {
            return $query;
        }

        return $query->where(
            'company_id',
            $user->company_id
        );
    }
}